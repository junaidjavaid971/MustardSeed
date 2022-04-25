// Toastr Options
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

// Login API Call

$("#btnLogin").click(function (e) {
  var email = $("#email-field").val();
  var password = $("#password-field").val();

  if (email === "") {
    toastr.error("Please enter the email");
    return false;
  } else if (!isEmail(email)) {
    toastr.warning("Please enter valid email address");
    return false;
  } else if (password === "") {
    toastr.error("Please enter the password");
    return false;
  }

  var loginData = {
    Data: {
      lvl: "1",
      email: email,
      password: password,
    },
  };

  var request = $.ajax({
    url: "services/api/login.php",
    type: "POST",
    data: loginData,
    dataType: "html",
  });

  request.done(function (msg) {
    var response = JSON.parse(jQuery.trim(msg));
    if (response.code === "00") {
      toastr.success(response.desc);

      if (response.data) {
        var imageUrl =
          "services/images/profile_pictures/" + email + ".png";
        imageExists(email, imageUrl);
      } else {
        storeSession(email, 0, 1);
        document.location.href =
          "dashboard-unverified-incomplete-profile.html?email=" +
          encodeURIComponent(email, true);
        document.location.href = url;
      }
    } else if (response.code === "02") {
      sendVerificationEmail(email);
    } else {
      toastr.error(response.desc);
    }
  });

  request.fail(function (jqXHR, textStatus) {
    toastr.error(textStatus);
  });
});

//Registration API Call

$("#btn-register").click(function (e) {
  var name = $("#name-field").val();
  var email = $("#email-field").val();
  var password = $("#password-field").val();

  if (name === "") {
    toastr.error("Please enter the name");
    return false;
  } else if (email === "") {
    toastr.error("Please enter the email");
    return false;
  } else if (!isEmail(email)) {
    toastr.warning("Please enter valid email address");
    return false;
  } else if (password === "") {
    toastr.error("Please enter the password");
    return false;
  }

  var registrationData = {
    Data: {
      name: name,
      email: email,
      password: password,
    },
  };

  var request = $.ajax({
    url: "services/api/register.php",
    type: "POST",
    data: registrationData,
    dataType: "html",
  });

  request.done(function (msg) {
    var response = JSON.parse(jQuery.trim(msg));
    if (response.code === "00") {
      var url =
        "account-verification-otp.html?email=" +
        encodeURIComponent(email, true);
      document.location.href = url;
      toastr.success(response.desc);
    } else {
      toastr.error(response.desc);
    }
  });

  request.fail(function (jqXHR, textStatus) {
    toastr.error(textStatus);
  });
});

$("#open-mail-server").click(function (e) {
  window.open("https://" + $("#user-email").text().replace(/.*@/, ""));
});

//Reset Password (Level 1) API Call

$("#btn-reset-password").click(function (e) {
  var email = $("#email-field").val();
  if (email === "") {
    toastr.warning("Please enter your email");
    return false;
  } else if (!isEmail(email)) {
    toastr.warning("Please enter valid email address");
    return false;
  }

  var resetPassword = {
    Data: {
      email: email,
      lvl: 1,
    },
  };

  var request = $.ajax({
    url: "services/api/resetpassword.php",
    type: "POST",
    data: resetPassword,
    dataType: "html",
  });

  request.done(function (msg) {
    var response = JSON.parse(jQuery.trim(msg));
    if (response.code === "00") {
      document.location.href =
        "forgot-password-2.html?email=" + email;
      toastr.success(response.desc);
    } else {
      toastr.error(response.desc);
    }
  });

  request.fail(function (jqXHR, textStatus) {
    toastr.error(textStatus);
  });
});

//Reset Password (Level 2) API Call

$("#btn-verify-password-reset-code").click(function (e) {
  var email = getParameterFromURL("email");
  var verificationCode =
    $("#ver-code-1").val() +
    $("#ver-code-2").val() +
    $("#ver-code-3").val() +
    $("#ver-code-4").val();
  if (email === "") {
    return false;
  } else if (verificationCode === "") {
    toastr.warning("Please enter verification code");
    return false;
  } else if (verificationCode.length < 4) {
    toastr.warning("Invalid Verification Code");
    return false;
  }

  var resetPassword = {
    Data: {
      email: email,
      verCode: verificationCode,
      lvl: 2,
    },
  };

  var request = $.ajax({
    url: "services/api/resetpassword.php",
    type: "POST",
    data: resetPassword,
    dataType: "html",
  });

  request.done(function (msg) {
    var response = JSON.parse(jQuery.trim(msg));
    if (response.code === "00") {
      document.location.href =
        "forgot-password-hidden.html?email=" + email;
      toastr.success(response.desc);
    } else {
      toastr.error(response.desc);
    }
  });

  request.fail(function (jqXHR, textStatus) {
    toastr.error(textStatus);
  });
});

//Reset Password (Level 3) API Call

$("#btn-change-password").click(function (e) {
  var email = getParameterFromURL("email");
  var password = $("#password-field").val();
  var confirmPassword = $("#password-field-confirm").val();

  if (email === "") {
    return false;
  } else if (password === "") {
    toastr.warning("Please enter a password");
    return false;
  } else if (password !== confirmPassword) {
    toastr.warning("Password and confirm password does not match!");
    return false;
  }

  var resetPassword = {
    Data: {
      email: email,
      newPassword: password,
      lvl: 3,
    },
  };

  var request = $.ajax({
    url: "services/api/resetpassword.php",
    type: "POST",
    data: resetPassword,
    dataType: "html",
  });

  request.done(function (msg) {
    var response = JSON.parse(jQuery.trim(msg));
    if (response.code === "00") {
      document.location.href = "password-reset-successful.html";
      toastr.success(response.desc);
    } else {
      toastr.error(response.desc);
    }
  });

  request.fail(function (jqXHR, textStatus) {
    toastr.error(textStatus);
  });
});

//Google Sign up API Call

$("#btn-google-signup").click(function (e) {
  document.location.href = "services/api/google-sign-in.php";
});

// Helper Functions

function storeSession(email, lvl, act) {
  var sessionData = {
    Data: {
      email: email,
      lvl: lvl,
      act: 1,
    },
  };

  var request = $.ajax({
    url: "services/api/session.php",
    type: "POST",
    data: sessionData,
    dataType: "html",
  });

  request.done(function (msg) {
    console.log(msg);
  });

  request.fail(function (jqXHR, textStatus) {
    console.log(textStatus);
  });
}

function getParameterFromURL(name) {
  var url = new URL(window.location.href);
  var c = url.searchParams.get(name);
  console.log(c);
  return c;
}

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function imageExists(email, fileUrl) {
  $.ajax({
    url: fileUrl,
    type: "HEAD",
    error: function () {
      storeSession(email, 1, 1);
      document.location.href =
        "verified-dashboard.html?email=" +
        encodeURIComponent(email, true);
      document.location.href = url;
    },
    success: function () {
      var cvUrl = "services/files/docs/cv/" + email + ".pdf";
      cvExists(email, cvUrl);
    },
  });
}

function cvExists(email, fileUrl) {
  $.ajax({
    url: fileUrl,
    type: "HEAD",
    error: function () {
      storeSession(email, 2, 1);
      document.location.href =
        "cv-resume-dashboard.html?email=" +
        encodeURIComponent(email, true);
      document.location.href = url;
    },
    success: function () {
      checkSupervisor(email);
    },
  });
}

function checkSupervisor(email) {
  var nominatedSupervisor = {
    Data: {
      email: email,
      lvl: 10,
    },
  };

  var request = $.ajax({
    url: "services/api/profile.php",
    type: "POST",
    data: nominatedSupervisor,
    dataType: "html",
  });

  request.done(function (msg) {
    var response = JSON.parse(jQuery.trim(msg));
    console.log(response);
    switch (response) {
      case 0:
        storeSession(email, 3, 1);
        document.location.href =
          "nominate-supervisor.html?email=" +
          encodeURIComponent(email, true);
        document.location.href = url;
        break;
      case 1:
        storeSession(email, 4, 1);
        document.location.href =
          "dbs-validation-request.html?email=" +
          encodeURIComponent(email, true);
        document.location.href = url;
        break;
      case 2:
        storeSession(email, 5, 1);
        document.location.href =
          "9-membership.html?email=" +
          encodeURIComponent(email, true);
        document.location.href = url;
        break;
      case 3:
        storeSession(email, 6, 1);
        document.location.href =
          "10-membership-requested.html?email=" +
          encodeURIComponent(email, true);
        document.location.href = url;
        break;
      default:
      // code block
    }
  });

  request.fail(function (jqXHR, textStatus) {
    toastr.error(textStatus);
  });
}

function sendVerificationEmail(email) {
  var verificationEmailPayload = {
    Data: {
      email: email,
      lvl: 4,
    },
  };

  var request = $.ajax({
    url: "services/api/resetpassword.php",
    type: "POST",
    data: verificationEmailPayload,
    dataType: "html",
  });

  request.done(function (msg) {
    var response = JSON.parse(jQuery.trim(msg));
    if (response.code === "00") {
      document.location.href =
        "account-verification-otp.html?email=" +
        encodeURIComponent(email, true);
      document.location.href = url;
    }
  });

  request.fail(function (jqXHR, textStatus) {
    toastr.error(textStatus);
  });
}
