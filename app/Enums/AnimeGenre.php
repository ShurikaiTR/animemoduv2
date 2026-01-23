<?php

declare(strict_types=1);

namespace App\Enums;

enum AnimeGenre: string
{
    case ACTION = 'aksiyon';
    case CARS = 'arabalar';
    case MILITARY = 'askeri';
    case AVANT_GARDE = 'avangard';
    case SCI_FI = 'bilim-kurgu';
    case MAGIC = 'buyu';
    case KIDS = 'cocuklar';
    case SUPERNATURAL = 'dogaustu-gucler';
    case MARTIAL_ARTS = 'dovus-sanatlari';
    case DRAMA = 'dram';
    case ECCHI = 'ecchi';
    case FANTASY = 'fantastik';
    case THRILLER = 'gerilim';
    case MYSTERY = 'gizem';
    case HAREM = 'harem';
    case JOSEI = 'josei';
    case COMEDY = 'komedi';
    case HORROR = 'korku';
    case ADVENTURE = 'macera';
    case MECHA = 'mecha';
    case MUSIC = 'muzik';
    case SCHOOL = 'okul';
    case GAME = 'oyun';
    case PARODY = 'parodi';
    case POLICE = 'polisiye';
    case PSYCHOLOGICAL = 'psikolojik';
    case ROMANCE = 'romantizm';
    case SAMURAI = 'samuray';
    case SEINEN = 'seinen';
    case DEMONS = 'seytanlar';
    case SHOUJO = 'shoujo';
    case SHOUJO_AI = 'shoujo-ai';
    case SHOUNEN = 'shounen';
    case SHOUNEN_AI = 'shounen-ai';
    case SPORTS = 'spor';
    case SUPER_POWER = 'super-gucler';
    case HISTORICAL = 'tarihi';
    case SPACE = 'uzay';
    case VAMPIRE = 'vampir';
    case YAOI = 'yaoi';
    case SLICE_OF_LIFE = 'yasamdan-kesitler';
    case YURI = 'yuri';

    public function label(): string
    {
        return match ($this) {
            self::ACTION => 'Aksiyon',
            self::CARS => 'Arabalar',
            self::MILITARY => 'Askeri',
            self::AVANT_GARDE => 'Avangard',
            self::SCI_FI => 'Bilim Kurgu',
            self::MAGIC => 'Büyü',
            self::KIDS => 'Çocuklar',
            self::SUPERNATURAL => 'Doğaüstü Güçler',
            self::MARTIAL_ARTS => 'Dövüş Sanatları',
            self::DRAMA => 'Dram',
            self::ECCHI => 'Ecchi',
            self::FANTASY => 'Fantastik',
            self::THRILLER => 'Gerilim',
            self::MYSTERY => 'Gizem',
            self::HAREM => 'Harem',
            self::JOSEI => 'Josei',
            self::COMEDY => 'Komedi',
            self::HORROR => 'Korku',
            self::ADVENTURE => 'Macera',
            self::MECHA => 'Mecha',
            self::MUSIC => 'Müzik',
            self::SCHOOL => 'Okul',
            self::GAME => 'Oyun',
            self::PARODY => 'Parodi',
            self::POLICE => 'Polisiye',
            self::PSYCHOLOGICAL => 'Psikolojik',
            self::ROMANCE => 'Romantizm',
            self::SAMURAI => 'Samuray',
            self::SEINEN => 'Seinen',
            self::DEMONS => 'Şeytanlar',
            self::SHOUJO => 'Shoujo',
            self::SHOUJO_AI => 'Shoujo Ai',
            self::SHOUNEN => 'Shounen',
            self::SHOUNEN_AI => 'Shounen Ai',
            self::SPORTS => 'Spor',
            self::SUPER_POWER => 'Süper Güçler',
            self::HISTORICAL => 'Tarihi',
            self::SPACE => 'Uzay',
            self::VAMPIRE => 'Vampir',
            self::YAOI => 'Yaoi',
            self::SLICE_OF_LIFE => 'Yaşamdan Kesitler',
            self::YURI => 'Yuri',
        };
    }

    /**
     * Get all values as array
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get options for select inputs
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }
}
