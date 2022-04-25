// Toastr Options
var role = "";
var mobileNumber = "";
var contactNumber = "";
var address = "";
var country = "";
var state = "";
var city = "";
var zipCode = "";
var accreditation = "";
var qualification = "";
var college = "";
var passingYear = "";
var supervisorMobileNumber = "";
var supervisorContactNumber = "";
var supervisorAddress = "";
var supervisorCountry = "";
var supervisorState = "";
var supervisorCity = "";
var supervisorZipCode = "";

var organizationName = "";
var employeeDesignation = "";
var organizationMobileNumber = "";
var organizationContactNumber = "";
var organizationAddress = "";
var organizationCountry = "";
var organizationState = "";
var organizationCity = "";
var organizationZipCode = "";
var organizationEmail = "";
var organizationType = "";

var companyRegNo = "";
var directorName = "";
var numberOfYearsTrading = "";
var numberOfTherapists = "";
var numberOfManagers = "";
var isBusinessRegistered = "";
var ofstedNumber = "";
var sencoDetails = "";
var headTeacherDetails = "";
var charityNumber = "";
var responsibleTrustee = "";

var clientSuperviser = "";
var notclientSuperviser = "";
var agreeDeclaration = "";
var agreeTerms = "";
var agreePri = "";

toastr.options = {
  closeButton: true,
  newestOnTop: false,
  progressBar: true,
  positionClass: "toast-bottom-right",
  preventDuplicates: true,
  onclick: null,
  showDuration: "300",
  hideDuration: "1000",
  timeOut: "5000",
  extendedTimeOut: "1000",
  showEasing: "swing",
  hideEasing: "linear",
  showMethod: "fadeIn",
  hideMethod: "fadeOut",
};

// User Profile Validations and API Call

$("#btn-save-personal-info").click(function (e) {
  // Profile Data
  role = $("#spiner-role :selected").text();
  mobileNumber = $("#mobile-number").val();
  contactNumber = $("#country-code :selected").text() + mobileNumber;
  address = $("#address").val();
  country = $("#spinner-country :selected").text();
  state = $("#state").val();
  city = $("#city").val();
  zipCode = $("#zip-code").val();

  console.log(address);

  if (role === "Select Role") {
    toastr.warning("Please select a role");
    return false;
  } else if (mobileNumber === "") {
    toastr.warning("Please enter your mobile number");
    return false;
  } else if (address === "") {
    toastr.warning("Please enter your address");
    return false;
  } else if (country === "Country") {
    toastr.warning("Please select your country");
    return false;
  } else if (state === "") {
    toastr.warning("Please enter your state");
    return false;
  } else if (city === "") {
    toastr.warning("Please enter your city");
    return false;
  } else if (zipCode === "") {
    toastr.warning("Please enter your zip code");
    return false;
  } else {
    if (role === "Therapist") {
      $("#profileCompletionFormTwo").modal("hide");
      $("#profileCompletionFormThree").modal("show");
    } else if (role === "Manager") {
      $("#profileCompletionFormTwo").modal("hide");
      $("#profileCompletionFormManager").modal("show");
    } else if (role === "Local Authority (LA)" || role === "Business User") {
      $("#profileCompletionFormTwo").modal("hide");
      $("#profileCompletionFormLA").modal("show");
    }
  }
});

$("#btn-save-accreditation").click(function (e) {
  // Accreditation & Qualification
  accreditation = $("#accreditation").val();
  qualification = $("#qualification").val();
  college = $("#college").val();
  passingYear = $("#passing-year").val();

  if (accreditation === "") {
    toastr.warning("Please enter the accreditation");
    return false;
  } else if (qualification === "") {
    toastr.warning("Please enter your qualification");
    return false;
  } else if (college === "") {
    toastr.warning("Please enter your College/University");
    return false;
  } else if (passingYear === "") {
    toastr.warning("Please select passing year.");
    return false;
  } else {
    $("#profileCompletionFormThree").modal("hide");
    $("#profileCompletionFormFour").modal("show");
  }
});

$("#btn-save-supervisor-contact-details").click(function (e) {
  // Supervisor Profile Data
  supervisorMobileNumber = +$("#supervisor-mobile-number").val();
  supervisorContactNumber =
    $("#supervisor-country-code :selected").text() + supervisorMobileNumber;
  supervisorAddress = $("#supervisor-address").val();
  supervisorCountry = $("#supervisor-country :selected").text();
  supervisorState = $("#supervisor-state").val();
  supervisorCity = $("#supervisor-city").val();
  supervisorZipCode = $("#supervisor-zip-code").val();

  if (supervisorMobileNumber === "") {
    toastr.warning("Please enter the supervisor's mobile number.");
    return false;
  } else if (supervisorAddress === "") {
    toastr.warning("Please enter the supervisor's address.");
    return false;
  } else if (supervisorCountry === "Country") {
    toastr.warning("Please select the supervisor's country");
    return false;
  } else if (supervisorState === "") {
    toastr.warning("Please enter the supervisor's state.");
    return false;
  } else if (supervisorCity === "") {
    toastr.warning("Please enter the supervisor's city.");
    return false;
  } else if (supervisorZipCode === "") {
    toastr.warning("Please enter the supervisor's zip code.");
    return false;
  } else {
    $("#profileCompletionFormFour").modal("hide");
    $("#profileCompletionFormFive").modal("show");
  }
});


$("#btn-save-organization-details").click(function (e) {
  // Supervisor Profile Data
  orgnizationName = +$("#organization-name").val();
  employeeDesignation = +$("#employee-designation").val();
  organizationMobileNumber = +$("#organization-mobile-number").val();
  organizationContactNumber =
    $("#manager-country-code :selected").text() + organizationMobileNumber;
  organizationAddress = $("#organization-address").val();
  organizationCountry = $("#organization-country :selected").text();
  organizationState = $("#organization-state").val();
  organizationCity = $("#organization-city").val();
  organizationZipCode = $("#organization-zip-code").val();

  if (orgnizationName === "") {
    toastr.warning("Please enter the organization name.");
    return false;
  } else if (employeeDesignation === "") {
    toastr.warning("Please enter the employee's designation.");
    return false;
  } else if (organizationMobileNumber === "") {
    toastr.warning("Please enter the organization's mobile number.");
    return false;
  } else if (organizationAddress === "") {
    toastr.warning("Please enter the organization's address.");
    return false;
  } else if (organizationCountry === "Country") {
    toastr.warning("Please select the organization's country");
    return false;
  } else if (organizationState === "") {
    toastr.warning("Please enter the organization's state.");
    return false;
  } else if (organizationCity === "") {
    toastr.warning("Please enter the organization's city.");
    return false;
  } else if (organizationZipCode === "") {
    toastr.warning("Please enter the organization's zip code.");
    return false;
  } else {
    $("#profileCompletionFormManager").modal("hide");
    $("#declarationModal").modal("show");
  }
});

$("#btn-save-la-details").click(function (e) {
  // Supervisor Profile Data
  organizationName = $("#la-name").val();
  organizationEmail = $("#la-email").val();
  organizationType = $("#organization-type :selected").text();
  organizationMobileNumber = $("#la-mobile-number").val();
  organizationContactNumber =
    $("#la-country-code :selected").text() + organizationMobileNumber;
  organizationAddress = $("#la-address").val();
  organizationCountry = $("#la-country :selected").text();
  organizationState = $("#la-state").val();
  organizationCity = $("#la-city").val();
  organizationZipCode = $("#la-zip-code").val();

  if (organizationName == "") {
    toastr.warning("Please enter the organization name.");
    return false;
  } else if (organizationEmail == "") {
    toastr.warning("Please enter the organization's email address.");
    return false;
  } else if (!isEmail(organizationEmail)) {
    toastr.warning("Please enter a valid email address.");
    return false;
  } else if (
    !/@gov.uk\s*$/.test(organizationEmail) &&
    !/@nhs.uk\s*$/.test(organizationEmail) &&
    !/@nhs.net\s*$/.test(organizationEmail)
  ) {
    toastr.warning("Organization email should end with gov.uk/nhs.uk/nhs.net");
    return false;
  } else if (organizationType === "Organization Type") {
    toastr.warning("Please select the organization type.");
    return false;
  } else if (organizationContactNumber == "") {
    toastr.warning("Please enter the organization's mobile number.");
    return false;
  } else if (organizationAddress === "") {
    toastr.warning("Please enter the organization's address.");
    return false;
  } else if (organizationCountry == "Country") {
    toastr.warning("Please select the organization's country");
    return false;
  } else if (organizationState == "") {
    toastr.warning("Please enter the organization's state.");
    return false;
  } else if (organizationCity == "") {
    toastr.warning("Please enter the organization's city.");
    return false;
  } else if (organizationZipCode == "") {
    toastr.warning("Please enter the organization's zip code.");
    return false;
  } else {
    if (organizationType == "Local Authority") {
      $("#profileCompletionFormLA").modal("hide");
      $("#declarationModal").modal("show");
    } else if (organizationType == "Business") {
      $("#profileCompletionFormLA").modal("hide");
      $("#profileCompletionFormBusinessDetails").modal("show");
      $("#school-details").hide();
      $("#charity-details").hide();
    } else if (organizationType == "School") {
      $("#profileCompletionFormLA").modal("hide");
      $("#profileCompletionFormBusinessDetails").modal("show");
      $("#charity-details").hide();
    } else if (organizationType == "Charity") {
      $("#profileCompletionFormLA").modal("hide");
      $("#profileCompletionFormBusinessDetails").modal("show");
      $("#school-details").hide();
    }
  }
});

$("#btn-save-business-details").click(function (e) {
  // Supervisor Profile Data
  companyRegNo = $("#company-reg-no").val();
  directorName = $("#director-name").val();
  numberOfYearsTrading = $("#years-trading").val();
  numberOfTherapists = $("#no-of-therapists").val();
  numberOfManagers = $("#no-of-managers").val();
  isBusinessRegistered = $('input[name="flexRadioDefault"]:checked').val();
  organizationCity = $("#la-city").val();
  ofstedNumber = $("#ofsted-reg-no").val();
  sencoDetails = $("#senco-details").val();
  headTeacherDetails = $("#head-teacher-details").val();
  charityNumber = $("#charity-number").val();
  responsibleTrustee = $("#responsible-trustee").val();

  if (companyRegNo == "") {
    toastr.warning("Please enter Company's Registration Number.");
    return false;
  } else if (directorName == "") {
    toastr.warning("Please enter Director's Name.");
    return false;
  } else if (numberOfYearsTrading == "") {
    toastr.warning("Please enter the number of years trading.");
    return false;
  } else if (numberOfTherapists === "") {
    toastr.warning("Please enter the number of therapists.");
    return false;
  } else if (numberOfManagers == "") {
    toastr.warning("Please enter the number of managers.");
    return false;
  } else if (organizationType == "School") {
    if (ofstedNumber == "") {
      toastr.warning("Please enter OFSTED Registration Number.");
      return false;
    } else if (sencoDetails == "") {
      toastr.warning("Please enter SENCO Details.");
      return false;
    } else if (headTeacherDetails == "") {
      toastr.warning("Please enter SENCO Details.");
      return false;
    } else {
      $("#profileCompletionFormBusinessDetails").modal("hide");
      $("#declarationModal").modal("show");
    }
  } else if (organizationType == "School") {
    if (charityNumber == "") {
      toastr.warning("Please enter Charity Number.");
      return false;
    } else if (responsibleTrustee == "") {
      toastr.warning("Please enter information about Responsible Trustee.");
      return false;
    } else {
      $("#profileCompletionFormBusinessDetails").modal("hide");
      $("#declarationModal").modal("show");
    }
  } else {
    $("#profileCompletionFormBusinessDetails").modal("hide");
    $("#declarationModal").modal("show");
  }
});

$("#btn-save-client-supervisor").click(function (e) {
  // Accreditation & Qualification
  clientSuperviser = $("#Yes").is(":checked");
  notclientSuperviser = $("#No").is(":checked");

  if (!clientSuperviser && !notclientSuperviser) {
    toastr.warning("Please provide an answer");
  } else if (clientSuperviser && notclientSuperviser) {
    toastr.warning("Please select one option");
  } else {
    $("#profileCompletionFormFive").modal("hide");
    $("#declarationModal").modal("show");
  }
});

$("#btn-save-dec").click(function (e) {
  // Accreditation & Qualification
  agreeDeclaration = $("#checkbox-dec").is(":checked");

  if (!agreeDeclaration) {
    toastr.warning("Please agree to the decalartion in order to continue");
  } else {
    $("#declarationModal").modal("hide");
    $("#termsCond").modal("show");
  }
});

$("#btn-save-terms").click(function (e) {
  // Accreditation & Qualification
  agreeTerms = $("#checkbox-term").is(":checked");

  if (!agreeTerms) {
    toastr.warning(
      "Please agree to the Terms & Conditions in order to continue"
    );
  } else {
    $("#termsCond").modal("hide");
    $("#privacyplo").modal("show");
  }
});

$("#btn-accept").click(function (e) {
  // Accreditation & Qualification
  agreePri = $("#checkbox-pri").is(":checked");
  var membership = "bronze";
  var email = getParameterFromURL("email");
  if (!agreePri) {
    toastr.warning("Please agree to the Privacy Policy in order to continue");
  } else {
    var storeProfile = {
      Data: {
        lvl: 1,
        name: email,
        email: email,
        role: role,
        contactNumber: contactNumber,
        address: address,
        country: country,
        state: state,
        city: city,
        zipCode: zipCode,
        accreditation: accreditation,
        qualification: qualification,
        college: college,
        membership: membership,
        passingYear: passingYear,
        organizationName: organizationName,
        employeeDesignation: employeeDesignation,
        organizationContactNumber: organizationContactNumber,
        organizationAddress: organizationAddress,
        organizationCountry: organizationCountry,
        organizationState: organizationState,
        organizationCity: organizationCity,
        organizationZipCode: organizationZipCode,
        organizationEmail: organizationEmail,
        organizationType: organizationType,
        companyRegNo: companyRegNo,
        directorName: directorName,
        numberOfYearsTrading: numberOfYearsTrading,
        numberOfTherapists: numberOfTherapists,
        numberOfManagers: numberOfManagers,
        isBusinessRegistered: isBusinessRegistered,
        ofstedNumber: ofstedNumber,
        sencoDetails: sencoDetails,
        headTeacherDetails: headTeacherDetails,
        charityNumber: charityNumber,
        responsibleTrustee: responsibleTrustee,
        supervisorContactNumber: supervisorContactNumber,
        supervisorAddress: supervisorAddress,
        supervisorCountry: supervisorCountry,
        supervisorState: supervisorState,
        supervisorCity: supervisorCity,
        supervisorZipCode: supervisorZipCode,

        clientSuperviser: clientSuperviser,
        declaration: agreeDeclaration,
        terms: agreeTerms,
        privacyPolicy: agreePri,
      },
    };
    var request = $.ajax({
      url: "services/api/profile.php",
      type: "POST",
      data: storeProfile,
      dataType: "html",
    });

    request.done(function (msg) {
      var response = JSON.parse(jQuery.trim(msg));
      if (response.code === "00") {
        document.location.href =
          "verified-dashboard.html?email=" + email;
        toastr.success(response.desc);
      } else {
        toastr.error(response.desc);
      }
    });

    request.fail(function (jqXHR, textStatus) {
      toastr.error(textStatus);
    });
  }
});



function getParameterFromURL(name) {
  var url = new URL(window.location.href);
  var c = url.searchParams.get(name);
  console.log(c);
  return c;
}

function savePicture(imgUrl) {
  fetch(imgUrl)
    .then((r) => r.blob())
    .then((blob) => {
      var reader = new FileReader();
      reader.onload = function () {
        var b64 = reader.result.replace(/^data:.+;base64,/, "");
        var savePicture = {
          Data: {
            lvl: 2,
            img: b64,
            email: getParameterFromURL("email"),
          },
        };

        var request = $.ajax({
          url: "services/api/profile.php",
          type: "POST",
          data: savePicture,
          dataType: "html",
        });

        request.done(function (msg) {
          var response = JSON.parse(jQuery.trim(msg));
          if (response.code === "00") {
            toastr.success(response.desc);
            $("#profilePicSelect").modal("hide");
            $("#picUploadSuccessfully").modal("show");
          } else {
            toastr.error(response.desc);
          }
        });

        request.fail(function (jqXHR, textStatus) {
          toastr.error(textStatus);
        });
      };
      reader.readAsDataURL(blob);
    });
}

function uploadCV(cvUrl, fileExtension) {
  fetch(cvUrl)
    .then((r) => r.blob())
    .then((blob) => {
      var reader = new FileReader();
      reader.onload = function () {
        var b64 = reader.result.replace(/^data:.+;base64,/, "");
        var uploadCV = {
          Data: {
            lvl: 3,
            base64: b64,
            email: getParameterFromURL("email"),
          },
        };

        var request = $.ajax({
          url: "services/api/profile.php",
          type: "POST",
          data: uploadCV,
          dataType: "html",
        });

        request.done(function (msg) {
          var response = JSON.parse(jQuery.trim(msg));
          if (response.code === "00") {
            toastr.success(response.desc);
            $("#selectCV").modal("hide");
            $("#cvSuccessfully").modal("show");
          } else {
            toastr.error(response.desc);
          }
        });

        request.fail(function (jqXHR, textStatus) {
          toastr.error(textStatus);
        });
      };
      reader.readAsDataURL(blob);
    });
}

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

