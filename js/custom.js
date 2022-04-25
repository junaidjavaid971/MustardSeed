// lef side nav script

$("#menu-toggle").click(function (e) {
    e.preventDefault();
    $(".all-wrapper").toggleClass("toggled");
});

//view password
$(".toggle-password").click(function () {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});

// 2 step authentication form

var Container = $('.fieldset-form');
var listItems = $('#progressbar li');
var nextBtn = $('.nextStep');
var nextBtn = $('.previousStep');
var submitBTn = $('.submitStep');

$(Container).eq(0).css({ 'display': 'block', 'z-index': '9' });
$(".nextStep").click(function () {
    var number = $(this).parent().index();
    $(this).parent().css({ 'display': 'none', 'z-index': '0' });
    $(this).parent().next().css({ 'display': 'block', 'z-index': '9' });
    $(listItems).eq(number).addClass("active");



    for (var i = 0; i < listItems.length; i++) {
        console.log(i < number);
        if (i < number) {
            $(listItems[i]).addClass("done_item");
        }
    }


})
$(".previousStep").click(function () {
    var number = $(this).parent().index();
    $(this).parent().css({ 'display': 'none', 'z-index': '0' });
    $(this).parent().prev().css({ 'display': 'block', 'z-index': '9' });
    $(listItems).eq(number - 1).removeClass("active");

    for (var i = 0; i < listItems.length; i++) {
        $(listItems[i]).removeClass("done_item");
        if (i < (number - 2)) {
            $(listItems[i]).addClass("done_item");
        }
    }

    console.log(number);
})
$(".submitStep").click(function () {
    return false;
})

// date popup
$(document).ready(function () {
    var date_input = $('input[name="date"]');
    var container = $('#profileCompletionFormThree');
    var options = {
        format: 'dd/mm/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,

    };
    date_input.datepicker(options);
})

// calender date
$(document).ready(function () {
    var date_input2 = $('input[name="calenderdate"]');
    var container = $('.calender-date');
    var options = {
        format: 'yyyy-mm-dd',
        container: container,
        todayHighlight: true,
        autoclose: true
    };
    date_input2.on("change", function () {
        var selected = $(this).val();
        $('#calendar').fullCalendar("gotoDate", selected);
    });
    date_input2.datepicker(options);
})


// chart date
$(document).ready(function () {
    var date_input3 = $('input[name="chartdate"]');
    var container = $('.calender-date');
    var options = {
        format: 'yyyy-dd-mm',
        container: container,
        todayHighlight: true,
        autoclose: true,
    };
    date_input3.datepicker(options);
})


// full calender
var bookingDate;
var bookingStartTime;
var bookingEndTime;
$(function () {
    let now = moment();
    let today = now.clone().startOf('day');
    $('#calendar').fullCalendar({

        defaultView: 'agendaDay',
        allDayText: 'Booking Hours',
        selectable: true,
        groupByResource: true,
        resources: [{
            id: 'a',
            title: 'Pick a Booking Slot'
        },
        ],
        select: function (start, end, allDay) {
            bookingStartTime = start.format("HH:mm");
            bookingEndTime = end.format("HH:mm");

            var jq = $.noConflict();
            jq(function () {
                bookingDate = $('input[name="calenderdate"]').val();
                if (bookingDate == "") {
                    bookingDate = now.format("YYYY-MM-DD");
                }
                jq('#bookingDetailsModalForm').modal('show');
                $('#booking-time-date').text(bookingDate + " (" + bookingStartTime + " - " + bookingEndTime + ")");
            });
        },

    });
});

var patientName = "";
var patientDetails = "";
var cardHolderName = "";
var cardName = "";
var expiry = "";
var cardCVC = "";
var topupAmount = "";

$('#btn-save-patient-details').click(function (e) {
    patientName = $('#patient-name').val();
    patientDetails = $('#patient-details').val();
    cardHolderName = $('#card-holder-name').val();
    cardNumber = $('#card-number').val();
    expiryMonth = $('#expiry-month').val();
    expiryYear = $('#expiry-year').val();
    cardCVC = $('#card-cvc').val();

    if (patientName == "") {
        toastr.warning("Please provide patient name");
    } else if (patientDetails == "") {
        toastr.warning("Please provide patient details");
    } else if (cardHolderName == "") {
        toastr.warning("Cardholder name cannot be empty!");
    } else if (cardNumber == "") {
        toastr.warning("Card number cannot be empty!");
    } else if (expiryMonth == "" || expiryMonth.length < 2) {
        toastr.warning("Empty or invalid expiry month!");
    } else if (expiryYear == "" || expiryYear.length < 4) {
        toastr.warning("Empty or invalid expiry year!");
    } else if (cardCVC == "" || cardCVC.length < 2) {
        toastr.warning("Empty or invalid CVC code!");
    } else {
        checkCardExpiry(expiryMonth, expiryYear);
        // $('#bookingDetailsModalForm').modal("hide");
        Stripe.setPublishableKey('pk_test_J45Zuv886PKjpl2VmlKnByWg');
        Stripe.createToken({
            number: cardNumber,
            cvc: cardCVC,
            exp_month: expiryMonth,
            exp_year: expiryYear.slice(-2)
        }, handleStripeResponse);
    }
});

$('#btn-save-patient-details').click(function (e) {
    patientName = $('#patient-name').val();
    patientDetails = $('#patient-details').val();
    cardHolderName = $('#card-holder-name').val();
    cardNumber = $('#card-number').val();
    expiryMonth = $('#expiry-month').val();
    expiryYear = $('#expiry-year').val();
    cardCVC = $('#card-cvc').val();

    if (patientName == "") {
        toastr.warning("Please provide patient name");
    } else if (patientDetails == "") {
        toastr.warning("Please provide patient details");
    } else if (cardHolderName == "") {
        toastr.warning("Cardholder name cannot be empty!");
    } else if (cardNumber == "") {
        toastr.warning("Card number cannot be empty!");
    } else if (expiryMonth == "" || expiryMonth.length < 2) {
        toastr.warning("Empty or invalid expiry month!");
    } else if (expiryYear == "" || expiryYear.length < 4) {
        toastr.warning("Empty or invalid expiry year!");
    } else if (cardCVC == "" || cardCVC.length < 2) {
        toastr.warning("Empty or invalid CVC code!");
    } else {
        checkCardExpiry(expiryMonth, expiryYear);
        // $('#bookingDetailsModalForm').modal("hide");
        Stripe.setPublishableKey('pk_test_J45Zuv886PKjpl2VmlKnByWg');
        Stripe.createToken({
            number: cardNumber,
            cvc: cardCVC,
            exp_month: expiryMonth,
            exp_year: expiryYear.slice(-2)
        }, handleStripeResponse);
    }
});

$('#btn-topup-balance').click(function (e) {
    cardHolderName = $('#topup-card-holder-name').val();
    cardNumber = $('#topup-card-number').val();
    expiryMonth = $('#topup-expiry-month').val();
    expiryYear = $('#topup-expiry-year').val();
    cardCVC = $('#topup-card-cvc').val();
    topupAmount = $('#topup-amount').val();

    if (cardHolderName == "") {
        toastr.warning("Cardholder name cannot be empty!");
    } else if (cardNumber == "") {
        toastr.warning("Card number cannot be empty!");
    } else if (expiryMonth == "" || expiryMonth.length < 2) {
        toastr.warning("Empty or invalid expiry month!");
    } else if (expiryYear == "" || expiryYear.length < 4) {
        toastr.warning("Empty or invalid expiry year!");
    } else if (cardCVC == "" || cardCVC.length < 2) {
        toastr.warning("Empty or invalid CVC code!");
    } else if (topupAmount == "") {
        toastr.warning("Please enter a topup amount!");
    } else {
        checkCardExpiry(expiryMonth, expiryYear);
        Stripe.setPublishableKey('pk_test_J45Zuv886PKjpl2VmlKnByWg');
        Stripe.createToken({
            number: cardNumber,
            cvc: cardCVC,
            exp_month: expiryMonth,
            exp_year: expiryYear.slice(-2)
        }, handleWalletResponse);
    }
});

$('#btn-pay-with-wallet').click(function (e) {
    patientName = $('#patient-name').val();
    patientDetails = $('#patient-details').val();
    var amount = parseInt($(".balance").text());
    var therapistEmail = getParameterFromURL('t_email');

    if (patientName == "") {
        toastr.warning("Patient name cannot be empty!");
    } else if (patientDetails == "") {
        toastr.warning("Patient detail cannot be empty!");
    } else if (amount < 50) {
        toastr.warning("Your balance is low. Please topup the amount to book the therapy session!");
    } else {
        var stripePayload = {
            Data: {
                name: patientName,
                desc: patientDetails,
                date: bookingDate,
                therapistEmail: therapistEmail,
                startTime: bookingStartTime,
                endTime: bookingEndTime,
                lvl: 7
            },
        };

        var jq = jQuery.noConflict();
        jq(function () {
            var request = jq.ajax({
                url: "services/api/booking.php",
                type: "POST",
                data: stripePayload,
                dataType: "html",
            });

            request.done(function (msg) {
                var response = JSON.parse(jQuery.trim(msg));
                if (response.code === "00") {
                    jq("#bookingDetailsModalForm").modal("hide");
                    toastr.success("Booking confirmed!");
                    document.location.href = "booking-detail.html?b_id=" + response.desc;
                    document.location.href = url;
                } else {
                    toastr.error(response.desc);
                }
            });

            request.fail(function (jqXHR, textStatus) {
                toastr.error(textStatus);
            });
        });
    }
});


// Helper Functions
function handleStripeResponse(status, response) {
    console.log(JSON.stringify(response));
    if (response.error) {
        alert(response.error.message);
    } else {
        getEmail(response['id']);
    }
}

function handleWalletResponse(status, response) {
    console.log(JSON.stringify(response));
    if (response.error) {
        alert(response.error.message);
    } else {
        sendWalletResponse(response['id']);
    }
}

function sendWalletResponse(stripeToken) {
    toastr.info("Please wait");
    var stripePayload = {
        Data: {
            token: stripeToken,
            amount: topupAmount,
            lvl: 5
        },
    };
    var request = $.ajax({
        url: "services/api/booking.php",
        type: "POST",
        data: stripePayload,
        dataType: "html",
    });

    request.done(function (msg) {
        var response = JSON.parse(jQuery.trim(msg));
        if (response.code === "00") {
            $("#paymentInformationModal").modal("hide");
            getTopupAmount();
        } else {
            toastr.error(response.desc);
        }
    });

    request.fail(function (jqXHR, textStatus) {
        toastr.error(textStatus);
    });
}

function sendPaymentRequest(email, stripeToken) {
    var therapistEmail = getParameterFromURL('t_email');
    toastr.info("Please wait");
    var stripePayload = {
        Data: {
            token: stripeToken,
            name: patientName,
            email: email,
            therapistEmail: therapistEmail,
            desc: patientDetails,
            date: bookingDate,
            startTime: bookingStartTime,
            endTime: bookingEndTime,
            lvl: 1
        },
    };
    var jq = jQuery.noConflict();

    jq(function () {
        var request = jq.ajax({
            url: "services/api/booking.php",
            type: "POST",
            data: stripePayload,
            dataType: "html",
        });

        request.done(function (msg) {
            var response = JSON.parse(jQuery.trim(msg));
            if (response.code === "00") {
                var jq = jQuery.noConflict();
                jq(function () {
                    jq("#bookingDetailsModalForm").modal("hide");
                });
                toastr.success("Booking confirmed!");
                document.location.href = "booking-detail.html?b_id=" + response.desc;
                document.location.href = url;
            } else {
                toastr.error(response.desc);
            }
        });

        request.fail(function (jqXHR, textStatus) {
            toastr.error(textStatus);
        });
    });
}

function getTopupAmount() {
    var walletPayload = {
        Data: {
            lvl: 6
        },
    };
    var request = $.ajax({
        url: "services/api/booking.php",
        type: "POST",
        data: walletPayload,
        dataType: "html",
    });

    request.done(function (msg) {
        $(".balance").text(msg);
    });

    request.fail(function (jqXHR, textStatus) {
        toastr.error(textStatus);
    });
}

function checkCardExpiry(expiry_month, expiry_year) {
    var d = new Date();
    var currentYear = d.getFullYear();
    var currentMonth = d.getMonth() + 1;
    // get parts of the expiration date

    // compare the dates
    if (expiry_year < currentYear || (expiry_year == currentYear && expiry_month < currentMonth)) {
        toastr.error("The expiry date has passed.");
        result = false;
    }
}

function redirectToTherapistPage(userType) {
    switch (userType) {
        case "user":
            document.location.href = "booked-sessions.html";
            break;
        default:
            document.location.href = "sessions-therapist.html";
            break;
    }
}

function getProfile() {
    var getUserProfile = {
        Data: {
            lvl: 17
        }
    }

    var request = $.ajax({
        url: "services/api/profile.php",
        type: "POST",
        data: getUserProfile,
        dataType: "html"
    });

    request.done(function (msg) {
        var response = JSON.parse(jQuery.trim(msg));
        if (response.code === "00") {
            userType = response.data;
            redirectToTherapistPage(userType)
        } else {
            getProfile();
        }
    });

    request.fail(function (jqXHR, textStatus) {
        toastr.error(textStatus);
    });
}

function getEmail(token) {
    var sessionData = {
        Data: {
            email: "",
            lvl: "",
            act: 3,
        },
    };

    var jq = jQuery.noConflict();
    jq(function () {
        var request = jq.ajax({
            url: "services/api/session.php",
            type: "POST",
            data: sessionData,
            dataType: "html",
        });

        request.done(function (msg) {
            if (msg != "") {
                const data = msg.split("-");
                var email = data[1];
                sendPaymentRequest(email, token);
            } else {
                toastr.error("Session logged out. Please login again!")
            }
        });
    });
}

// table therapy left show
jQuery(document).ready(function () {

    var $this = $('.item-list');
    if ($this.find('tr').length > 5) {
        $('.item-list').append('<span class="show-more-less"><a href="javascript:;" class="showMore"></a></span>');
    }

    // If more than 2 Education items, hide the remaining
    $('.item-list tr').slice(0, 5).addClass('shown');
    $('.item-list tr').not('.shown').hide();
    $('.item-list .showMore').on('click', function () {
        $('.item-list tr').not('.shown').toggle(300);
        $(this).toggleClass('showLess');
    });

});


// table therapy left show
jQuery(document).ready(function () {

    var $this = $('.item-list-right');
    if ($this.find('tr').length > 4) {
        $('.item-list-right').append('<span class="show-more-less-right"><a href="javascript:;" class="showMore-right"></a></span>');
    }

    // If more than 2 Education items, hide the remaining
    $('.item-list-right tr').slice(0, 4).addClass('shown-right');
    $('.item-list-right tr').not('.shown-right').hide();
    $('.item-list-right .showMore-right').on('click', function () {
        $('.item-list-right tr').not('.shown-right').toggle(300);
        $(this).toggleClass('showLess-right');
    });

});


// therapy-chart-top
var colors = Highcharts.getOptions().colors;

Highcharts.chart('therapy-chart-top', {
    chart: {
        type: 'spline'
    },

    legend: {
        symbolWidth: 40
    },

    title: {
        text: ''
    },

    subtitle: {
        text: ''
    },

    yAxis: {
        title: {
            text: ''
        }
    },

    xAxis: {
        title: {
            text: 'Time'
        },
        accessibility: {
            description: ''
        },
        categories: ['Dec', 'May', 'Jan', 'July', 'Oct', 'Sep']
    },

    tooltip: {
        valueSuffix: '%'
    },

    plotOptions: {
        series: {
            point: {
                events: {
                    click: function () {
                        window.location.href = this.series.options.website;
                    }
                }
            },
            cursor: 'pointer'
        }
    },

    series: [
        {
            name: 'NVDA',
            data: [34.58, 43.0, 151.2, 41.4, 164.9, 72.4],
            website: 'https://www.nvaccess.org',
            color: colors[2],
            accessibility: {
                description: ''
            }
        }, {
            name: 'JAWS',
            data: [69.6, 163.7, 63.9, 143.7, 66.0, 161.7],
            website: 'https://www.freedomscientific.com/Products/Blindness/JAWS',
            dashStyle: 'ShortDashDot',
            color: colors[0]
        }, {
            name: 'VoiceOver',
            data: [120.2, 30.7, 136.8, 30.9, 139.6, 47.1],
            website: 'http://www.apple.com/accessibility/osx/voiceover',
            dashStyle: 'ShortDot',
            color: colors[1]
        }, {
            name: 'ZoomText/Fusion',
            data: [16.1, 6.8, 15.3, 127.5, 6.0, 5.5],
            website: 'http://www.zoomtext.com/products/zoomtext-magnifierreader',
            dashStyle: 'ShortDot',
            color: colors[5]
        }
    ],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 550
            },
            chartOptions: {
                legend: {
                    itemWidth: 150
                },
                xAxis: {
                    categories: ['Dec', 'May', 'Jan', 'July', 'Oct', 'Sep']
                },
                yAxis: {
                    title: {
                        enabled: false
                    },
                    labels: {
                        format: '{value}%'
                    }
                }
            }
        }]
    }
});