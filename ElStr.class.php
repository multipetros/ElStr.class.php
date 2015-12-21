<?php
/**
 * Class with usefull functions for modern greek unicode text manipulation
 * @version 1.0
 * @author Petros Kyladitis <petros.kyladitis@gmail.com>
 * @copyright Copyright (c) 2013, Petros Kyladitis
 * @license FreeBSD License <http://www.multipetros.gr/freebsd-license/>
 */
class El_Str{
	
	const UTF8 = 'UTF-8' ;
	const TOUPPER = 'mb_strtoupper' ;
	
	//
	// arrays for to_latin function usage
	//
	
	//difthongs
	protected $el_difthongs = array('αι','αί','οι','οί','ου','ού','ει','εί','ντ','τσ','τζ','γγ','γκ','γχ','γξ','θ','χ','ψ') ;
	protected $lat_difthongs = array('ai','ai','oi','oi','ou','ou','ei','ei','nt','ts','tz','ng','gk','nch','nx','th','ch','ps') ;
	
	//*υ difthongs case, if followed by letters at pos 1-3 converted to *f, else to *v
	protected $el_spec_difthongs = array(
		'(α[υ|ύ])(?![α|ά|β|γ|δ|ε|έ|ζ|η|ή|λ|ι|ί|ϊ|ΐ|μ|ν|ο|ό|ρ|ω|ώ|])', 
		'(ε[υ|ύ])(?![α|ά|β|γ|δ|ε|έ|ζ|η|ή|λ|ι|ί|ϊ|ΐ|μ|ν|ο|ό|ρ|ω|ώ|])', 
		'(η[υ|ύ])(?![α|ά|β|γ|δ|ε|έ|ζ|η|ή|λ|ι|ί|ϊ|ΐ|μ|ν|ο|ό|ρ|ω|ώ|])', 
		'(α[υ|ύ])', 
		'(ε[υ|ύ])', 
		'(η[υ|ύ])',
		'(Α[υ|ύ])(?![α|ά|β|γ|δ|ε|έ|ζ|η|ή|λ|ι|ί|ϊ|ΐ|μ|ν|ο|ό|ρ|ω|ώ|])',
		'(Ε[υ|ύ])(?![α|ά|β|γ|δ|ε|έ|ζ|η|ή|λ|ι|ί|ϊ|ΐ|μ|ν|ο|ό|ρ|ω|ώ|])',
		'(Η[υ|ύ])(?![α|ά|β|γ|δ|ε|έ|ζ|η|ή|λ|ι|ί|ϊ|ΐ|μ|ν|ο|ό|ρ|ω|ώ|])',
		'(Α[υ|ύ])',
		'(Ε[υ|ύ])',
		'(Η[υ|ύ])',
		'(Α[Υ|Ύ])(?![Α|Ά|Β|Γ|Δ|Ε|Έ|Ζ|Η|Ή|Λ|Ι|Ί|Ϊ|Μ|Ν|Ο|Ό|Ρ|Ω|Ώ|])',
		'(Ε[Υ|Ύ])(?![Α|Ά|Β|Γ|Δ|Ε|Έ|Ζ|Η|Ή|Λ|Ι|Ί|Ϊ|Μ|Ν|Ο|Ό|Ρ|Ω|Ώ|])',
		'(Η[Υ|Ύ])(?![Α|Ά|Β|Γ|Δ|Ε|Έ|Ζ|Η|Ή|Λ|Ι|Ί|Ϊ|Μ|Ν|Ο|Ό|Ρ|Ω|Ώ|])',
		'(Α[Υ|Ύ])',
		'(Ε[Υ|Ύ])',
		'(Η[Υ|Ύ])'
		) ;
	protected $lat_spec_difthongs = array('af','ef','if','av','ev','iv', 'Af','Ef','If','Av','Ev','Iv', 'AF','EF','IF','AV','EV','IV') ;
	
	//μπ difthong case, inner word 'μπ' converted to 'mp', 'μπ' at word boundaries with 'b'
	protected $el_mp_difthong =  array('\\Bμπ\\B','μπ' ) ;
	protected $lat_mp_difthong = array('mp', 'b') ;
	
	//one fthong letters convertions
	protected $el_letters = array('α|ά','β','γ','δ','ε|έ','ζ','η|ή|ι|ί|ϊ|ΐ','κ','λ','μ','ν','ξ','ο|ό|ω|ώ','π','ρ','σ|ς','τ','υ|ύ|ϋ|ΰ','φ') ;
	protected $lat_letters = array('a','v','g','d','e','z','i','k','l','m','n','x','o','p','r','s','t','y','f') ;
	
	//
	// arrays for accent replacements usage
	//
	protected $upper_accent_letters = array('Ά','Έ','Ή', 'Ί|Ϊ', 'Ό', 'Ύ|Ϋ', 'Ώ') ;
	protected $upper_no_accent_letters = array('Α','Ε','Η', 'Ι', 'Ο', 'Υ', 'Ω') ;
	
	/**
	 * Check if $char is upper case
	 * @param string $char character for checking
	 * @param bool $notGreekException throw exception if char is not greek
	 * @return bool true if $char is upper case, else false
     * @throws Exception if $notGreekException is true and not $char is not modern greek
	 */
	public function is_upper($char, $notGreekException = false){
		//from variable $char, get only the first character (in case of giving string).
		$char = mb_substr($char, 0, 1, 'UTF-8') ;
		if($notGreekException){
			if(($char < 'Ά') || ($char > 'ώ')){
				// Modern greek unicode chars start from hex 0386 (CAPITAL ALPHA WITH TONOS) until
				// hex 03CE (SMALL OMEGA WITH TONOS). So, outside of this range considers as non greek char.
				throw new Exception("$char is not a Greek unicode letter") ;
			}
		}
		// ά (hex 03AC) is the border between upper and lower case chars,
		// ecxept ΐ (hex 0390) who is positioned between capitals (.-Ώ-ΐ-Α-.)
		// so, check for this letter, else return T/F by ά positioned
		if($char == 'ΐ')
			return true ;
		return $char < 'ά' ? true : false ;
	}
	
	/**
	 * Check if $char is lower case
	 * @param string $char character for checking
	 * @param bool $notGreekException throw exception if char is not greek
	 * @return true if $char is lower case, else false
	 */
	public function is_lower($char, $notGreekException = false){
		//call is_upper function and reverse the returned value
		return $this->is_upper($char, $notGreekException) ? false : true ;
	}
	
	/**
	 * Convert greek letters at the string to latins, as ISO:843 defines
	 * @param string $str string to convert
	 * @return string converted string
	 */
	public function to_latin($str){
		//do regex replacements, starting from difthongs, 1-fthong letters at the end
		$str = $this->replace_letters($this->el_difthongs, $this->lat_difthongs, $str) ;
		$str = $this->replace_letters($this->el_mp_difthong, $this->lat_mp_difthong, $str) ;
		$str = $this->replace_letters($this->el_spec_difthongs, $this->lat_spec_difthongs, $str) ;
		$str = $this->replace_letters($this->el_letters, $this->lat_letters, $str) ;
		
		//do replacements for UPPER CASE letters. use array_map to make uppers the letters from all arrays
		mb_internal_encoding('UTF-8'); //set encoding to use one variable ver of mb_strtoupper
		$str = $this->replace_letters(array_map('mb_strtoupper', $this->el_difthongs), array_map('mb_strtoupper', $this->lat_difthongs), $str) ;
		$str = $this->replace_letters(array_map('mb_strtoupper', $this->el_mp_difthong), array_map('mb_strtoupper', $this->lat_mp_difthong), $str) ;
		$str = $this->replace_letters(array_map('mb_strtoupper', $this->el_spec_difthongs), array_map('mb_strtoupper', $this->lat_spec_difthongs), $str) ;
		$str = $this->replace_letters(array_map('mb_strtoupper', $this->el_letters), array_map('mb_strtoupper', $this->lat_letters), $str) ;
		
		return $str ;
	}
	
	/**
	 * Replace the letters of a string defined at the 1st array, with letters defined at the 2nd array
	 * (for example, 1st array:['a','b'] - 2nd array:['y','z'] - string: "abstract" => "yzstryct")
	 * @param array $current_letters_array character for checking
	 * @param array $become_letters_array throw exception if char is not greek
	 * @param string $str string to do the replacements
	 * @return string with replaced letters
	 */
	private function replace_letters($current_letters_array, $become_letters_array, $str){
		//set utf-8 encoding for regullar expersions
		mb_regex_encoding('UTF-8') ;
		//for each letter in convertion arrays, do a regex replacement at the string
		for($i = 0; $i < count($current_letters_array); $i++){
			$str = mb_ereg_replace($current_letters_array[$i], $become_letters_array[$i], $str) ;
		}
		return $str ;
	}
	
	/**
	 * Convert unicode string to upper case, without accent marks for the greek letters
	 * @param string $str string to upper case convert
	 * @return string converted string
	 */
	public function strtoupper_no_accent($str){
		//convert unicode string to upper, and then replace accent marked letters with no accent
		$str = mb_strtoupper($str, 'UTF-8') ;
		$str = $this->replace_letters($this->upper_accent_letters, $this->upper_no_accent_letters, $str) ;
		return $str ;
	}
}

?>
