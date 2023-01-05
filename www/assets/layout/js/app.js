function formValidation() 
{
  // Make quick references to our fields.
  var jobNumber = document.getElementById("jobNumber");
  var pdffile = document.getElementById("fileToUpload");

  if (validateFileType(pdffile, "* Please select a pdf file *")) {
    if (textNumeric(jobNumber, "* Please enter a valid job Number *")) {
      if (lengthDefine(jobNumber, 6, 6)) {
        return true;
      }
    }
  }
  return false;
}

// Function that checks whether input text is numeric or not.
function textNumeric(inputtext, alertMsg) {
  var numericExpression = /^[0-9]+$/;

  if (inputtext.value.match(numericExpression)) {
    return true;
  } else {
    document.getElementById("p6").innerText = alertMsg; // This segment displays the validation rule for zip.
    inputtext.focus();
    return false;
  }
}

function validateFileType(inputtext, alertMsg) {

    var files = inputtext.files;
    if(files.length==0){
        document.getElementById("p4").innerText = alertMsg; // This segment displays the validation rule for zip.
        return false;
    }else{
        var filename = files[0].name;

        /* getting file extenstion eg- .jpg,.png, etc */
        var extension = filename.substr(filename.lastIndexOf("."));

        /* define allowed file types */
        var allowedExtensionsRegx = /(\.pdf)$/i;

        /* testing extension with regular expression */
        var isAllowed = allowedExtensionsRegx.test(extension);

        if(!isAllowed){
            document.getElementById("p4").innerText = alertMsg; 
            return false;
        }
        return true;

    }
}

// Function that checks whether the input characters are restricted according to defined by user.
function lengthDefine(inputtext, min, max) {
  var uInput = inputtext.value;
  if (uInput.length >= min && uInput.length <= max) {
    return true;
  } else {
    document.getElementById("p6").innerText =      "* Please enter between " + min + " and " + max + " characters *"; // This segment displays the validation rule for username
    inputtext.focus();
    return false;
  }
}


function popup(mylink, windowname,width=800,height=400)
{
   if (! window.focus)return true;
   var href; 
   if (typeof(mylink) == 'string') href=mylink; else href=mylink.href;
   window.open(href, windowname, 'width='+width+',height='+height+',scrollbars=yes'); 
   return false; 
} 
