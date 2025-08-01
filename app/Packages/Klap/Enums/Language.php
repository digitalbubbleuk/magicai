<?php

namespace App\Packages\Klap\Enums;

use App\Concerns\HasEnumConvert;

enum Language: string
{
    use HasEnumConvert;

    case AUTO = 'auto';
    case ENGLISH = 'en';
    case CHINESE = 'zh';
    case GERMAN = 'de';
    case SPANISH = 'es';
    case RUSSIAN = 'ru';
    case KOREAN = 'ko';
    case FRENCH = 'fr';
    case JAPANESE = 'ja';
    case PORTUGUESE = 'pt';
    case TURKISH = 'tr';
    case POLISH = 'pl';
    case CATALAN = 'ca';
    case DUTCH = 'nl';
    case ARABIC = 'ar';
    case SWEDISH = 'sv';
    case ITALIAN = 'it';
    case INDONESIAN = 'id';
    case HINDI = 'hi';
    case FINNISH = 'fi';
    case VIETNAMESE = 'vi';
    case HEBREW = 'he';
    case UKRAINIAN = 'uk';
    case GREEK = 'el';
    case MALAY = 'ms';
    case CZECH = 'cs';
    case ROMANIAN = 'ro';
    case DANISH = 'da';
    case HUNGARIAN = 'hu';
    case TAMIL = 'ta';
    case NORWEGIAN = 'no';
    case THAI = 'th';
    case URDU = 'ur';
    case CROATIAN = 'hr';
    case BULGARIAN = 'bg';
    case LITHUANIAN = 'lt';
    case LATIN = 'la';
    case MAORI = 'mi';
    case MALAYALAM = 'ml';
    case WELSH = 'cy';
    case SLOVAK = 'sk';
    case TELUGU = 'te';
    case PERSIAN = 'fa';
    case LATVIAN = 'lv';
    case BENGALI = 'bn';
    case SERBIAN = 'sr';
    case AZERBAIJANI = 'az';
    case SLOVENIAN = 'sl';
    case KANNADA = 'kn';
    case ESTONIAN = 'et';
    case MACEDONIAN = 'mk';
    case BRETON = 'br';
    case BASQUE = 'eu';
    case ICELANDIC = 'is';
    case ARMENIAN = 'hy';
    case NEPALI = 'ne';
    case MONGOLIAN = 'mn';
    case BOSNIAN = 'bs';
    case KAZAKH = 'kk';
    case ALBANIAN = 'sq';
    case SWAHILI = 'sw';
    case GALICIAN = 'gl';
    case MARATHI = 'mr';
    case PUNJABI = 'pa';
    case SINHALA = 'si';
    case KHMER = 'km';
    case SHONA = 'sn';
    case YORUBA = 'yo';
    case SOMALI = 'so';
    case AFRIKAANS = 'af';
    case OCCITAN = 'oc';
    case GEORGIAN = 'ka';
    case BELARUSIAN = 'be';
    case TAJIK = 'tg';
    case SINDHI = 'sd';
    case GUJARATI = 'gu';
    case AMHARIC = 'am';
    case YIDDISH = 'yi';
    case LAO = 'lo';
    case UZBEK = 'uz';
    case FAROESE = 'fo';
    case HAITIAN_CREOLE = 'ht';
    case PASHTO = 'ps';
    case TURKMEN = 'tk';
    case NYNORSK = 'nn';
    case MALTESE = 'mt';
    case SANSKRIT = 'sa';
    case LUXEMBOURGISH = 'lb';
    case MYANMAR = 'my';
    case TIBETAN = 'bo';
    case TAGALOG = 'tl';
    case MALAGASY = 'mg';
    case ASSAMESE = 'as';
    case TATAR = 'tt';
    case HAWAIIAN = 'haw';
    case LINGALA = 'ln';
    case HAUSA = 'ha';
    case BASHKIR = 'ba';
    case JAVANESE = 'jv';
    case SUNDANESE = 'su';

    public function label(): string
    {
        return match ($this) {
            self::AUTO           => 'Auto',
            self::ENGLISH        => 'English',
            self::CHINESE        => 'Chinese',
            self::GERMAN         => 'German',
            self::SPANISH        => 'Spanish',
            self::RUSSIAN        => 'Russian',
            self::KOREAN         => 'Korean',
            self::FRENCH         => 'French',
            self::JAPANESE       => 'Japanese',
            self::PORTUGUESE     => 'Portuguese',
            self::TURKISH        => 'Turkish',
            self::POLISH         => 'Polish',
            self::CATALAN        => 'Catalan',
            self::DUTCH          => 'Dutch',
            self::ARABIC         => 'Arabic',
            self::SWEDISH        => 'Swedish',
            self::ITALIAN        => 'Italian',
            self::INDONESIAN     => 'Indonesian',
            self::HINDI          => 'Hindi',
            self::FINNISH        => 'Finnish',
            self::VIETNAMESE     => 'Vietnamese',
            self::HEBREW         => 'Hebrew',
            self::UKRAINIAN      => 'Ukrainian',
            self::GREEK          => 'Greek',
            self::MALAY          => 'Malay',
            self::CZECH          => 'Czech',
            self::ROMANIAN       => 'Romanian',
            self::DANISH         => 'Danish',
            self::HUNGARIAN      => 'Hungarian',
            self::TAMIL          => 'Tamil',
            self::NORWEGIAN      => 'Norwegian',
            self::THAI           => 'Thai',
            self::URDU           => 'Urdu',
            self::CROATIAN       => 'Croatian',
            self::BULGARIAN      => 'Bulgarian',
            self::LITHUANIAN     => 'Lithuanian',
            self::LATIN          => 'Latin',
            self::MAORI          => 'Maori',
            self::MALAYALAM      => 'Malayalam',
            self::WELSH          => 'Welsh',
            self::SLOVAK         => 'Slovak',
            self::TELUGU         => 'Telugu',
            self::PERSIAN        => 'Persian',
            self::LATVIAN        => 'Latvian',
            self::BENGALI        => 'Bengali',
            self::SERBIAN        => 'Serbian',
            self::AZERBAIJANI    => 'Azerbaijani',
            self::SLOVENIAN      => 'Slovenian',
            self::KANNADA        => 'Kannada',
            self::ESTONIAN       => 'Estonian',
            self::MACEDONIAN     => 'Macedonian',
            self::BRETON         => 'Breton',
            self::BASQUE         => 'Basque',
            self::ICELANDIC      => 'Icelandic',
            self::ARMENIAN       => 'Armenian',
            self::NEPALI         => 'Nepali',
            self::MONGOLIAN      => 'Mongolian',
            self::BOSNIAN        => 'Bosnian',
            self::KAZAKH         => 'Kazakh',
            self::ALBANIAN       => 'Albanian',
            self::SWAHILI        => 'Swahili',
            self::GALICIAN       => 'Galician',
            self::MARATHI        => 'Marathi',
            self::PUNJABI        => 'Punjabi',
            self::SINHALA        => 'Sinhala',
            self::KHMER          => 'Khmer',
            self::SHONA          => 'Shona',
            self::YORUBA         => 'Yoruba',
            self::SOMALI         => 'Somali',
            self::AFRIKAANS      => 'Afrikaans',
            self::OCCITAN        => 'Occitan',
            self::GEORGIAN       => 'Georgian',
            self::BELARUSIAN     => 'Belarusian',
            self::TAJIK          => 'Tajik',
            self::SINDHI         => 'Sindhi',
            self::GUJARATI       => 'Gujarati',
            self::AMHARIC        => 'Amharic',
            self::YIDDISH        => 'Yiddish',
            self::LAO            => 'Lao',
            self::UZBEK          => 'Uzbek',
            self::FAROESE        => 'Faroese',
            self::HAITIAN_CREOLE => 'Haitian Creole',
            self::PASHTO         => 'Pashto',
            self::TURKMEN        => 'Turkmen',
            self::NYNORSK        => 'Nynorsk',
            self::MALTESE        => 'Maltese',
            self::SANSKRIT       => 'Sanskrit',
            self::LUXEMBOURGISH  => 'Luxembourgish',
            self::MYANMAR        => 'Myanmar',
            self::TIBETAN        => 'Tibetan',
            self::TAGALOG        => 'Tagalog',
            self::MALAGASY       => 'Malagasy',
            self::ASSAMESE       => 'Assamese',
            self::TATAR          => 'Tatar',
            self::HAWAIIAN       => 'Hawaiian',
            self::LINGALA        => 'Lingala',
            self::HAUSA          => 'Hausa',
            self::BASHKIR        => 'Bashkir',
            self::JAVANESE       => 'Javanese',
            self::SUNDANESE      => 'Sundanese',
        };
    }
}
