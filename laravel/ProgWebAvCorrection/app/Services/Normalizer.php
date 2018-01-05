<?php namespace App\Services;

/**
 * Normalizer pour les chaines de caractère utf-8 (Classe statique)
 *
 * http://www.julp.fr/articles/3-php-et-utf-8.html
 * http://www.unicode.org/Public/UNIDATA/UnicodeData.txt
 *
 */
class StringNormalizer
{
      const DEFAULT_LOCAL = 'FR_fr';

    /**
     * Locale
     *
     * @var string
     */
    private $_local;

    /**
     * Collator pour les comparaisons de chaine UTF-8
     *
     * @var Collator
     */
    private $_collator;

    /**
     * Niveau (strength) de comparaison pour le Collator
     *
     * @var Collator
     */
    private static $_collatorStrength;

    /**
     * Fonction d'initialisation des variables statiques
     */
    private static function initStatic()
    {
        if (!isset(self::$_local)) {
            self::setLocale(self::DEFAULT_LOCAL);
        }
    }

    /**
     * Change la locale par défaut
     *
     * @param string $local
     */
    public static function setLocale($local)
    {
        self::$_local = $local;
        setlocale(LC_COLLATE, self::$_local . '.UTF8');
        setlocale(LC_CTYPE, self::$_local . '.UTF8');
        self::$_collator = new Collator(self::$_local);
        if (!isset(self::$_collatorStrength)) {
            self::$_collatorStrength = Collator::PRIMARY;
        }
        self::$_collator->setStrength(self::$_collatorStrength);
    }

    /**
     * Change le "niveau" de comparaison du Collator. $strength peut prendre
     * les valeurs suivantes:
     *
     * - Collator::PRIMARY considère les caractères de base (A = a = à = À)
     * (C'est le niveau par défaut de la classe Synn_Normalizer)
     *
     * - Collator::SECONDARY seuls les accents apportent une distinction.
     * ( à = À mais à ≠ A et à ≠ a). Dans certaine langue, certain caractère
     * accentué sont quand même considéré comme identique.
     *
     * - Collator::TERTIARY est sensible à la casse (a ≠ A)
     *
     * @param int $strength
     */
    public static function setStrengthCollator($strength)
    {
        self::initStatic();
        self::$_collatorStrength = $strength;
        self::$_collator->setStrength(self::$_collatorStrength);
    }

    /**
     * Supprime tous les caractères diacritiques (et autres modificateurs de
     * caractères)
     *
     * @param string $str
     * @return string
     */
    public static function removeDiacritic($str)
    {
        self::initStatic();
        $str = self::normalize($str);
        return preg_replace('/\p{M}/u', '', $str);
    }


    /**
     * Supprime tous les caractères non alphabétiques d'une chaine UTF-8 (y
     * compris les diacritiques à moins que $diacritic soit à true)
     *
     * Laisse les caractères spécifés par le paramètre $allowed. $allowed doit
     * être un tableau de 'char' comprenant les caractères autorisés(en UTF-8)
     *
     * Si le paramètre $digit est à true, retourne aussi les chiffres (false par
     * défaut)
     *
     * Si le paramètre $diacritic est à true, laisse les diacritiques (true par
     * défaut)
     *
     * @param string $str
     * @param array $allowed
     * @param boolean $digit
     * @param boolean $diacritic
     * @return string
     */
    public static function getLetters($str, $allowed = array(),
            $digit = false, $diacritic = true)
    {
        $excep = 'getLettersDigitsAnd: $allowed must be an array of char';
        if (!is_array($allowed)) {
            throw new Exception($excep);
        }
        // accepte les digits si demandé
        $accepted = $digit ? '\d' : '';
        foreach ($allowed as $char) {
            // Vérifie que c'est bien un unique caractère
            if (mb_strlen($char) != 1) {
                throw new Exception($excep);
            }
            // Echappement des caractères interdits dans une classe PCRE
            if (in_array($char, array('^', '|', '-', ']', '\\'))) {
                $accepted .= '\\';
            }
            $accepted .= $char;
        }
        self::initStatic();
        // Supprime les diacritiques si demandé
        if (!$diacritic) {
            $str = self::removeDiacritic($str);
        } else {
            $str = self::normalize($str);
        }
        return preg_replace('/[^\p{L}' . $accepted . ']/u', '', $str);
    }

    /**
     * Supprime tous les caractères non alphanumériques d'une chaine UTF-8.
     * (Alias de la méthode getLetters en fixant son paramètre $digit à true)
     *
     * Laisse les caractères spécifés par le paramètre $allowed. $allowed doit
     * être un tableau de 'char' comprenant les caractères autorisés(en UTF-8)
     *
     * Si le paramètre $diacritic est à true, laisse les diacritiques (false par
     * défaut)
     *
     * @param string $str
     * @param array $allowed
     * @param boolean $diacritic
     * @return string
     */
    public static function getLettersAndDigits($str, $allowed = array(),
            $diacritic = false)
    {
        return self::getLetters($str, $allowed, true, $diacritic);
    }

    /**
     * Retourne la première lettre capitale d'une chaine ou la chaine vide si
     * aucune lettre majuscule n'est présente dans la chaine. Les diacritiques
     * de la lettre majuscule retournée sont supprimés (sauf si il n'y a pas
     * de lettre corespondante sans diacritique).
     *
     * @param string $str
     * @return string
     */
    public static function getFirstUppercase($str)
    {
        self::initStatic();
        $str = self::normalize($str);
        $matches = array();
        preg_match('/\p{Lu}/u', $str, $matches);
        return isset($matches[0]) ? $matches[0] : '';
    }

    /**
     * Supprime tous les caractères (espaces compris) avant la première lettre
     * majuscule d'une chaine. Retourne une chaine vide si aucune majuscule
     * n'est présente dans la chaine.
     *
     * @param string $str
     * @return string
     */
    public static function trimBeforeFirstUppercase($str)
    {
        self::initStatic();
        $str = self::normalize($str);
        return preg_replace('/^\P{Lu}*/u', '', $str);
    }

    /**
     * Normalisation d'une chaine au format Form D (NFD)
     *
     * @param string $str
     * @return string
     */
    public static function normalize($str)
    {
        return normalizer_normalize($str, Normalizer::FORM_D);
    }

    /**
     * Compare deux chaines de caractères. La comparaison est sensible ou
     * insensible à la casse et aux diacritiques en fonction du niveau spécifié
     * pour le Collator (voir la méthode self::setStrengthCollator)
     *
     * @param string $str1
     * @param string $str2
     * @return int
     */
    public static function compare($str1, $str2)
    {
        self::initStatic();
        return self::$_collator->compare($str1, $str2);
    }

}
