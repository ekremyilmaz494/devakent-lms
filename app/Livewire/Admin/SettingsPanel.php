<?php

namespace App\Livewire\Admin;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SettingsPanel extends Component
{
    public string $activeTab = 'general';

    // General Settings
    public string $institution_name = '';
    public string $institution_subtitle = '';
    public string $logo_url = '';
    public string $timezone = 'Europe/Istanbul';
    public string $default_language = 'tr';
    public bool $maintenance_mode = false;

    // Exam Defaults
    public int $default_exam_duration = 30;
    public int $default_passing_score = 70;
    public int $default_max_attempts = 3;
    public bool $shuffle_questions = true;
    public bool $show_results_immediately = true;

    // Session Security
    public int $session_timeout = 120;
    public int $max_login_attempts = 5;
    public bool $force_password_change = false;
    public int $password_min_length = 6;

    // Email Notifications
    public bool $email_on_enrollment = true;
    public bool $email_on_completion = true;
    public bool $email_on_certificate = true;
    public bool $email_weekly_report = false;

    public function mount(): void
    {
        // Load all settings from DB
        $this->institution_name = SystemSetting::get('institution_name', 'Devakent Hastanesi');
        $this->institution_subtitle = SystemSetting::get('institution_subtitle', 'Eğitim Yönetim Sistemi');
        $this->logo_url = SystemSetting::get('logo_url', '');
        $this->timezone = SystemSetting::get('timezone', 'Europe/Istanbul');
        $this->default_language = SystemSetting::get('default_language', 'tr');
        $this->maintenance_mode = (bool) SystemSetting::get('maintenance_mode', false);

        $this->default_exam_duration = (int) SystemSetting::get('default_exam_duration', 30);
        $this->default_passing_score = (int) SystemSetting::get('default_passing_score', 70);
        $this->default_max_attempts = (int) SystemSetting::get('default_max_attempts', 3);
        $this->shuffle_questions = (bool) SystemSetting::get('shuffle_questions', true);
        $this->show_results_immediately = (bool) SystemSetting::get('show_results_immediately', true);

        $this->session_timeout = (int) SystemSetting::get('session_timeout', 120);
        $this->max_login_attempts = (int) SystemSetting::get('max_login_attempts', 5);
        $this->force_password_change = (bool) SystemSetting::get('force_password_change', false);
        $this->password_min_length = (int) SystemSetting::get('password_min_length', 6);

        $this->email_on_enrollment = (bool) SystemSetting::get('email_on_enrollment', true);
        $this->email_on_completion = (bool) SystemSetting::get('email_on_completion', true);
        $this->email_on_certificate = (bool) SystemSetting::get('email_on_certificate', true);
        $this->email_weekly_report = (bool) SystemSetting::get('email_weekly_report', false);
    }

    public function saveGeneral(): void
    {
        $this->validate([
            'institution_name' => 'required|string|max:255',
            'institution_subtitle' => 'nullable|string|max:255',
        ]);

        $userId = Auth::id();
        SystemSetting::set('institution_name', $this->institution_name, $userId);
        SystemSetting::set('institution_subtitle', $this->institution_subtitle, $userId);
        SystemSetting::set('logo_url', $this->logo_url, $userId);
        SystemSetting::set('timezone', $this->timezone, $userId);
        SystemSetting::set('default_language', $this->default_language, $userId);
        SystemSetting::set('maintenance_mode', $this->maintenance_mode ? '1' : '0', $userId);

        session()->flash('success', 'Genel ayarlar kaydedildi.');
    }

    public function saveExam(): void
    {
        $this->validate([
            'default_exam_duration' => 'required|integer|min:5|max:180',
            'default_passing_score' => 'required|integer|min:1|max:100',
            'default_max_attempts' => 'required|integer|min:1|max:10',
        ]);

        $userId = Auth::id();
        SystemSetting::set('default_exam_duration', $this->default_exam_duration, $userId);
        SystemSetting::set('default_passing_score', $this->default_passing_score, $userId);
        SystemSetting::set('default_max_attempts', $this->default_max_attempts, $userId);
        SystemSetting::set('shuffle_questions', $this->shuffle_questions ? '1' : '0', $userId);
        SystemSetting::set('show_results_immediately', $this->show_results_immediately ? '1' : '0', $userId);

        session()->flash('success', 'Sınav varsayılanları kaydedildi.');
    }

    public function saveSecurity(): void
    {
        $this->validate([
            'session_timeout' => 'required|integer|min:5|max:480',
            'max_login_attempts' => 'required|integer|min:1|max:20',
            'password_min_length' => 'required|integer|min:4|max:32',
        ]);

        $userId = Auth::id();
        SystemSetting::set('session_timeout', $this->session_timeout, $userId);
        SystemSetting::set('max_login_attempts', $this->max_login_attempts, $userId);
        SystemSetting::set('force_password_change', $this->force_password_change ? '1' : '0', $userId);
        SystemSetting::set('password_min_length', $this->password_min_length, $userId);

        session()->flash('success', 'Güvenlik ayarları kaydedildi.');
    }

    public function saveEmail(): void
    {
        $userId = Auth::id();
        SystemSetting::set('email_on_enrollment', $this->email_on_enrollment ? '1' : '0', $userId);
        SystemSetting::set('email_on_completion', $this->email_on_completion ? '1' : '0', $userId);
        SystemSetting::set('email_on_certificate', $this->email_on_certificate ? '1' : '0', $userId);
        SystemSetting::set('email_weekly_report', $this->email_weekly_report ? '1' : '0', $userId);

        session()->flash('success', 'E-posta bildirim ayarları kaydedildi.');
    }

    public function render()
    {
        return view('livewire.admin.settings-panel');
    }
}
