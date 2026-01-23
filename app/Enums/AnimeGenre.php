<?php

declare(strict_types=1);

namespace App\Enums;

enum AnimeGenre: string
{
    case ACTION = 'aksiyon';
    case ADVENTURE = 'macera';
    case COMEDY = 'komedi';
    case DRAMA = 'dram';
    case ECCHI = 'ecchi';
    case FANTASY = 'fantastik';
    case HORROR = 'korku';
    case MAHOU_SHOUJO = 'buyulu-kiz';
    case MECHA = 'mecha';
    case MUSIC = 'muzik';
    case MYSTERY = 'gizem';
    case PSYCHOLOGICAL = 'psikolojik';
    case ROMANCE = 'romantizm';
    case SCI_FI = 'bilim-kurgu';
    case SLICE_OF_LIFE = 'hayattan-kesitler';
    case SPORTS = 'spor';
    case SUPERNATURAL = 'dogaustu';
    case THRILLER = 'gerilim';
    case HENTAI = 'hentai-18';
    case ISEKAI = 'isekai';
    case HAREM = 'harem';
    case SCHOOL = 'okul';
    case SEINEN = 'seinen';
    case SHOUJO = 'shoujo';
    case SHOUNEN = 'shounen';
    case JOSEI = 'josei';
    case VAMPIRE = 'vampir';
    case MILITARY = 'askeri';
    case MARTIAL_ARTS = 'dovus-sanatlari';
    case HISTORICAL = 'tarihi';

    public function label(): string
    {
        return match ($this) {
            self::ACTION => 'Aksiyon',
            self::ADVENTURE => 'Macera',
            self::COMEDY => 'Komedi',
            self::DRAMA => 'Dram',
            self::ECCHI => 'Ecchi',
            self::FANTASY => 'Fantastik',
            self::HORROR => 'Korku',
            self::MAHOU_SHOUJO => 'Büyülü Kız',
            self::MECHA => 'Mecha',
            self::MUSIC => 'Müzik',
            self::MYSTERY => 'Gizem',
            self::PSYCHOLOGICAL => 'Psikolojik',
            self::ROMANCE => 'Romantizm',
            self::SCI_FI => 'Bilim Kurgu',
            self::SLICE_OF_LIFE => 'Hayattan Kesitler',
            self::SPORTS => 'Spor',
            self::SUPERNATURAL => 'Doğaüstü',
            self::THRILLER => 'Gerilim',
            self::HENTAI => 'Hentai (+18)',
            self::ISEKAI => 'Isekai',
            self::HAREM => 'Harem',
            self::SCHOOL => 'Okul',
            self::SEINEN => 'Seinen',
            self::SHOUJO => 'Shoujo',
            self::SHOUNEN => 'Shounen',
            self::JOSEI => 'Josei',
            self::VAMPIRE => 'Vampir',
            self::MILITARY => 'Askeri',
            self::MARTIAL_ARTS => 'Dövüş Sanatları',
            self::HISTORICAL => 'Tarihi',
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
