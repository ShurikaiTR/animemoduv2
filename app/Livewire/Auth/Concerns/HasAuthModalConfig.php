<?php

declare(strict_types=1);

namespace App\Livewire\Auth\Concerns;

trait HasAuthModalConfig
{
    /**
     * Get the current view configuration.
     * @return array<string, string>
     */
    public function getConfig(): array
    {
        $configs = [
            'login' => [
                'title' => 'Tekrar Hoşgeldin!',
                'desc' => 'Sınırsız anime dünyasına giriş yap.',
                'image' => asset('img/auth/gojo.png'),
                'imageAlt' => 'Gojo Satoru',
                'accent' => 'from-primary to-primary-hover',
                'btnVariant' => 'primary',
                'formTitle' => 'Giriş Yap',
                'formDesc' => 'Devam etmek için e-posta ve şifreni gir.',
                'submitLabel' => 'Giriş Yap',
                'loadingLabel' => 'Giriş yapılıyor',
            ],
            'register' => [
                'title' => 'Aramıza Katıl!',
                'desc' => 'Kendi anime listeni oluştur ve maceraya başla.',
                'image' => asset('img/auth/luffy.png'),
                'imageAlt' => 'Monkey D. Luffy',
                'accent' => 'from-orange to-danger',
                'btnVariant' => 'orange',
                'formTitle' => 'Hesap Oluştur',
                'formDesc' => 'Sadece birkaç saniye içinde aramıza katıl.',
                'submitLabel' => 'Kayıt Ol',
                'loadingLabel' => 'Kaydediliyor',
            ],
            'forgot-password' => [
                'title' => 'Yolunu mu Kaybettin?',
                'desc' => 'Endişelenme, hesabını kurtarmana yardım edeceğiz.',
                'image' => asset('img/auth/zoro.png'),
                'imageAlt' => 'Roronoa Zoro',
                'accent' => 'from-success to-emerald-700',
                'btnVariant' => 'success',
                'formTitle' => 'Şifre Sıfırla',
                'formDesc' => 'E-posta adresini gir, sana bir bağlantı gönderelim.',
                'submitLabel' => 'Bağlantı Gönder',
                'loadingLabel' => 'Gönderiliyor',
            ],
        ];

        return $configs[$this->view] ?? $configs['login'];
    }
}
