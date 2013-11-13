ElStr.class.php
===============
A PHP Class with usefull functions for modern greek unicode text manipulation.  
Copyright (c) 2013, Petros Kyladitis - <http://www.multipetros.gr>  
Version 1.0  

Functions
---------

### `<string> to_latin($str)`
* Convert greek letters at the string to latins, as ISO:843 / екот:743 defines
* `$str` string to convert
* Returns converted string

---

### `<string> strtoupper_no_accent($str)`
* Convert unicode string to upper case, without accent marks for the greek letters
* `$str` string to upper case convert
* Returns converted string

---

### `<bool> is_upper($char, $notGreekException = false)`  
* Check if `$char` is upper case
* `$char` is the character for checking
* If `$notGreekException == true`, throws exception when char is not greek
- Returns `true` if `$char` is upper case, else `false`

---

### `<bool> is_lower($char, $notGreekException = false)`
* Check if `$char` is lower case
* `$char` is the character for checking
* If `$notGreekException == true`, throws exception when char is not greek
- Returns `true` if $char is lower case, else `false`


License
-------
This project is free software, distributed under the terms & conditions of the 
FreeBSD License. For more info see at the `LICENSE` file, distributed with this 
project. If not, visit <http://www.multipetros.gr/freebsd-license/>.