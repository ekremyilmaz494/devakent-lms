<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StaffImport implements ToCollection, WithHeadingRow
{
    public int $imported = 0;
    public int $skipped = 0;
    public array $errors = [];

    public function collection(Collection $rows): void
    {
        // Zorunlu kolon kontrolü
        if ($rows->isEmpty()) {
            throw new \InvalidArgumentException('CSV dosyası boş.');
        }

        $keys = $rows->first()->keys()->map(fn ($k) => mb_strtolower(trim($k)))->all();
        $missing = [];

        if (!in_array('ad', $keys, true)) {
            $missing[] = 'ad';
        }
        if (!in_array('soyad', $keys, true)) {
            $missing[] = 'soyad';
        }
        if (!in_array('eposta', $keys, true) && !in_array('e_posta', $keys, true)) {
            $missing[] = 'eposta';
        }

        if (!empty($missing)) {
            throw new \InvalidArgumentException(
                'CSV dosyasında zorunlu kolonlar eksik: ' . implode(', ', $missing)
            );
        }

        $departments = Department::where('is_active', true)->pluck('id', 'name');

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because heading row is 1, and index starts at 0

            $firstName = trim($row['ad'] ?? '');
            $lastName = trim($row['soyad'] ?? '');
            $email = trim($row['eposta'] ?? $row['e_posta'] ?? '');

            if (empty($firstName) || empty($lastName) || empty($email)) {
                $this->skipped++;
                $this->errors[] = "Satır {$rowNumber}: Ad, soyad veya e-posta boş.";
                continue;
            }

            if (User::withTrashed()->where('email', $email)->exists()) {
                $this->skipped++;
                // Silinmiş kullanıcı için daha açıklayıcı mesaj
                $isDeleted = User::withTrashed()->where('email', $email)->whereNotNull('deleted_at')->exists();
                $this->errors[] = $isDeleted
                    ? "Satır {$rowNumber}: {$email} daha önce silinmiş, sisteme tekrar eklenemez."
                    : "Satır {$rowNumber}: {$email} zaten kayıtlı.";
                continue;
            }

            // Departman eşleme
            $deptName = trim($row['departman'] ?? '');
            $departmentId = null;
            if ($deptName) {
                $departmentId = $departments->get($deptName);
                if (!$departmentId) {
                    // Kısmi eşleme dene
                    $departmentId = $departments->filter(function ($id, $name) use ($deptName) {
                        return str_contains(mb_strtolower($name), mb_strtolower($deptName));
                    })->first();
                }
            }

            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'name' => "{$firstName} {$lastName}",
                'email' => $email,
                'password' => Hash::make($row['sifre'] ?? 'password'),
                'phone' => trim($row['telefon'] ?? '') ?: null,
                'registration_number' => trim($row['sicil_no'] ?? '') ?: null,
                'title' => trim($row['unvan'] ?? '') ?: null,
                'department_id' => $departmentId,
                'is_active' => true,
            ]);

            $user->assignRole('staff');
            $this->imported++;
        }
    }
}
