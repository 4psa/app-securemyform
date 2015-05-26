/*
 * 4PSA VoipNow - CallAPI validate phone number
 *
 * Validates the phone number introduced by the user in the input field
 *
 * Copyright (c) 2010 Rack-Soft (www.4psa.com). All rights reserved.
 *
*/

/**
* Fetches a language message
* @param code - code of the message
*/
function getLangMsg(code){
	if(typeof(msg_arr[code]) != 'undefined') {
		return msg_arr[code]
	}
	return code;
} 

/**
	* Verifies the phone number value introduced in the input field
	* @return true - the value is ok
	* @return false - the is no value introduced
	*/  
function verifyFieldValue() {
	var fieldValue = document.getElementById('phone_number').value; 
	var check = true;
	if (fieldValue != "") {
		if (isNumeric(fieldValue)) {
			/* the value is a number (public number) */
		} else {
			/* the value is not a number */
			/* check if the value contains '+' */
			var plusIndex = fieldValue.lastIndexOf('+');
			if (plusIndex > -1) {
				/* the value contains '+' */
				if (plusIndex > 0) {
					/* the value contains '+' in another position than the first one */
					check = false;
				} else {
					/* it contains + on the first position */
					/* the rest of the value must be a number */
					if (isNumeric(fieldValue.substring(1)) == true) {	
					} else {
						/* the rest of the value is not a number */
						check = false;
					}
				}
			} else {
				/* the value dosn't contain '+' and is not a number*/
				/* check if the value has just one '*' */
				var starFIndex = fieldValue.indexOf('*');
				var starLIndex = fieldValue.lastIndexOf('*');
				
				if (starFIndex == starLIndex && starFIndex > 0) {
					/* the value has just one '*' */
					/* check if the '*' separates two numbers */
					var firstNumber = fieldValue.substring(0, starFIndex -1);
					var secondNumber = fieldValue.substring(starFIndex + 1);
					if (isNumeric(firstNumber) == true && isNumeric(secondNumber) == true) {
						/* the user introduced an extension number */
					} else {
						/* '*' doesn't separate two numbers */
						check = false;
					}
				} else {
					/* the value has more than one '*' */
					check = false;
				}
			}
		}
	} else {
		/* the value is null */
		check = false;
	}
	 if (check == false) {
		/* display an error message */
		var errorDiv = document.getElementById('warn_msg').style.display = "";
		var errorText = document.getElementById('warn_msg').innerHTML = getLangMsg('err_invalid_phone_number_entered');
		return false;
	} else {
		return true;
	}
}

/**
	* Verifies if a string represents a number
	* @param number - the string to verify
	* @return true - the string represents a number
	* @return false - the string doesn't represent a number
	*/
function isNumeric(number) {
	for (var i = 0; i < number.length; i++) {
		if (isFigure(number.charAt(i)) == false) {
			return false;
		}
	}
	return true;
}

/**
	* Verifies if a character represents a figure
	* @param element - the character to verify
	* @return true - the character represents a figure
	* @return false - the character doesn't represent a figure
	*/
function isFigure(element) {
	if (element == '0' || element == '1' || element == '2' || element == '3' || element == '4' || element == '5' || element == '6' || element == '7' || element == '8' || element == '9' ) {
		return true;
	}
	return false;
}


/* array with the random numbers that were validated */
/* usefull because the ajax requests for these numbers must be stopped */
var mustStop = new Array();
function getAJAXObject() {
	var xmlHttp;
	try
	{
		// Firefox, Opera 8.0+, Safari
		xmlHttp = new XMLHttpRequest();
	} catch (e) {
		// Internet Explorer
		try
		{
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try
			{
				xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				alert(getLangMsg('err_ajax_not_supported'));
				return false;
			}
		}
	}
	
	return xmlHttp;
}

/**
	* Requests the status of the attempt every 5 seconds
	* @param phoneNumber - the user's phone number
	* @param randomNumber - the random number that the user must introduce
	*/  
function getAttemptStatus(phoneNumber, randomNumber) {
	if (phoneNumber != "" && randomNumber != "") {
		if (isInArray(randomNumber, mustStop)) {
			/* if the ajax must be stopped */
			deleteFromArray(randomNumber, mustStop);
		} else {
			xmlHttpAttempt = getAJAXObject();
			if (xmlHttpAttempt.readyState == 4 || xmlHttpAttempt.readyState == 0) {
				var connString = "status.php?customer_number=" + phoneNumber + 
									"&random_number=" + randomNumber + 
									"&time="+new Date().getTime();
				xmlHttpAttempt.onreadystatechange = displayAttemptStatus;
				xmlHttpAttempt.open("GET", connString, true);
				xmlHttpAttempt.send(null);
				
				/* set the timer to 5 sec  */
				setTimeout('getAttemptStatus(\'' + phoneNumber + '\',' + randomNumber + ')' , 5000);
			}
		}
	}
}

/**
	* Displays the status of the attempt taken from the AJAX response
	*/
function displayAttemptStatus() {
	/* if the result is ready and the http status is ok */
	if (this.readyState == 4 && this.status == 200) {
		var response = this.responseText;
		var ok = 0;
		var div = document.getElementById('info_msg');
		div.style.display = "";
		if (response.charAt(0) == '0') {
			/* the phone is ringing or the customer answered */
			div.innerHTML = getLangMsg('status_enter_number');
			ok = 1;
		}
		if (response.charAt(0) == '1') {
			/* the number was not validated yet */
			var div = document.getElementById('info_msg');
			div.innerHTML = getLangMsg('status_number_validating');
			ok = 1;
		}
		if (response.charAt(0) == '2') {
			/* the attempt was correct */
			
			/* get the random number for which the attempt was correct, because the ajax with this number must stop */
			var pieces_good = new Array();
			pieces_good = response.split(",");
			if (pieces_good[1]) {
				insertInArray(pieces_good[1], mustStop);
			}
						
			var div = document.getElementById('info_msg');
			div.innerHTML = getLangMsg('status_correct_number');
			
			ok = 1;
					
			/* submit hidden form created in validate.php */
			var form = document.getElementById('securemyform');
			form.submit();
		} 
		if (response.charAt(0) == '3') {
			/* the attempt was wrong */
			
			/* get the random number for which the attempt was wrong, because the ajax with this number must stop */
			var pieces = new Array();
			pieces = response.split(",");
			if (pieces[2]) {
				insertInArray(pieces[2], mustStop);
			}
			
			var div = document.getElementById('info_msg');
			/* take from the response the number of attempts */
			var attemptsLeft = pieces[1];
			if (attemptsLeft == "0") {
				div.innerHTML = getLangMsg('status_max_attempts_reached');
				document.getElementById('warn_msg').innerHTML = getLangMsg('status_max_attempts_reached');
			}
			else {
				div.innerHTML = getLangMsg('status_incorrect_number').replace('{attemptsLeft}', attemptsLeft);
				document.getElementById('warn_msg').innerHTML = getLangMsg('status_incorrect_number').replace('{attemptsLeft}', attemptsLeft);
			}
			ok = 1;
		}
		
		if (response.charAt(0) == '4') {
			document.getElementById('warn_msg').innerHTML = "";
		}
		if (response.charAt(0) == '5') {
			document.getElementById('warn_msg').innerHTML = getLangMsg('err_processing_request');
		}
		if (ok == 0) {
			/* an error occurred; the ajax must stop */
			div.innerHTML = getLangMsg('err_establishing_connection');
			/* stop the ajax */
			var random_to_stop = document.getElementById('random_number').innerHTML;
			insertInArray(random_to_stop, mustStop);
		} 
	}
}

/**
 * Checks if an element exists in an Array
 * @param element - the element to check
 * @param vect - the array
 * @return true or false
*/
function isInArray(element, vect) {
	for (var i = 0; i < vect.length; i++) {
		if (vect[i] == element) {
			return true;
		}
	}
	return false;
} 

/**
 * Deletes an element from an Array
 * @param element - the element that must be deleted
 * @param vect - the Array
 * @return new_array - the new array with the element deleted
 */
function deleteFromArray(element, vect) {
	var index = getElementIndex(element, vect);
	var new_array = vect.splice(index,1);
	return new_array;
}
 
/**
 * Inserts an alement into an Array if the element is not already in the array
 * @param element - the element to insert 
 * @param vect - the array 
 */
function insertInArray(element, vect) {
	var index = getElementIndex(element, vect);
	if (index == -1) {
		vect.push(element);
	}
}

/**
 * Gets the index of a specified element from a vector
 * @param element - the element
 * @param vect - the vector
 * @return - the index of element in the vector if the element is in the vector, -1 if the element is not found in the vector
 */
function getElementIndex(element, vect) {
	for (var i = 0; i < vect.length; i++) {
		if (vect[i] == element) {
			return i;
		}
	}
	return -1;
} 