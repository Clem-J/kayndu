/**
 * Created by Clément on 17/08/2018.
 */

function kayndu_showEmailField() {
    // Get the checkbox
    var checkBox = document.getElementById("kayndu_option_sendCopy");
    // Get the output text
    var mailField = document.getElementById("mailField");

    // If the checkbox is checked, display the output text
    if (checkBox.checked == true){
        mailField.style.display = "block";
    } else {
        mailField.style.display = "none";
    }
}