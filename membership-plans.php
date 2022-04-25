<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Services</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/dashboard.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/custom.css" rel="stylesheet">

    <!-- plugin CSS -->
    <link href="css/plugin.css" rel="stylesheet">

    <!-- responsive css -->
    <link rel="stylesheet" href="css/responsive.css">

    <!-- Fontawesome CSS -->
    <link href="fontawesome/css/all.css" rel="stylesheet">
    <link rel='stylesheet' href='css/bootstrap-datepicker3.css'>

    <!-- Toastr CSS -->
    <link href="css/toastr.css" rel="stylesheet" />
</head>

<body class="dashboard-bg">
    <main role="main">

        <!-- start  container-fluid -->
        <div class="container-fluid p-0">
            <div class="all-wrapper">
                <!--start_aside-->
                <aside class="sidebar-left navbar navbar-inverse">
                    <div class="sidebar-left-inner">
                        <h2 class="m-0 mb-1 dashboard-logo">
                            <a href="/MustardSeed"><img src="images/dashboard-logo.png" alt=""></a>
                        </h2>
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle navbar-toggle-dasboard collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false"> <i class="fas fa-bars"></i> </button>
                        </div>
                        <div id="vertical-nav-tabs"></div>
                    </div>
                </aside>
                <!--end_aside-->

                <div class="clearfix"></div>
                <div class="all-content">
                    <div class="dashboard-main-wrapper">

                        <div class="dasboard-top-header"></div>

                        <div class="top-filter-area"></div>

                        <div class="col-md-12">
                            <div class="dashboard-middle-area">

                                <div class="row mr-0">
                                    <div class="col-md-6 col-sm-12 pt-2">
                                        <span class="back-span-top" onclick="goBack()"><i class="fas fa-arrow-left"></i></span>
                                        <h5 class="select-suv-h5 mb-4">Membership Plans <label></label></h5>
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                    </div>
                                    <div class="col-md-3 col-sm-12">
                                        <a href="">
                                            <button class="btn-upgrade-custom-package">(PAYG) Concierge Services></button>
                                        </a>
                                    </div>
                                </div>

                                <div class="row pt-2 mr-0 ml-0">
                                    <div class="table-responsive">
                                        <div class="membership-pricing-table">
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <th></th>
                                                        <th class="plan-header plan-header-free">
                                                            <div class="pricing-plan-name">BRONZE</div>
                                                            <div class="pricing-plan-price">
                                                                <sup>$</sup>0<span>.00</span>
                                                            </div>
                                                            <div class="pricing-plan-period">month</div>
                                                        </th>
                                                        <th class="plan-header plan-header-blue">
                                                            <div class="pricing-plan-name">SILVER</div>
                                                            <div class="pricing-plan-price">
                                                                <sup>$</sup>4<span>.99</span>
                                                            </div>
                                                            <div class="pricing-plan-period">month</div>
                                                        </th>
                                                        <th class="plan-header plan-header-blue">
                                                            <div class="pricing-plan-name">GOLD</div>
                                                            <div class="pricing-plan-price">
                                                                <sup>$</sup>12<span>.95</span>
                                                            </div>
                                                            <div class="pricing-plan-period">month</div>
                                                        </th>
                                                        <th class="plan-header plan-header-standard">
                                                            <div class="header-plan-inner">
                                                                <!--<span class="plan-head"> </span>-->
                                                                <span class="recommended-plan-ribbon">RECOMMENDED</span>
                                                                <div class="pricing-plan-name">STANDARD</div>
                                                                <div class="pricing-plan-price">
                                                                    <sup>$</sup>34<span>.99</span>
                                                                </div>
                                                                <div class="pricing-plan-period">month</div>
                                                            </div>
                                                        </th>
                                                        <th class="plan-header plan-header-blue">
                                                            <div class="pricing-plan-name">PLATINUM</div>
                                                            <div class="pricing-plan-price">
                                                                <sup>$</sup>99<span>.99</span>
                                                            </div>
                                                            <div class="pricing-plan-period">month</div>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td></td>

                                                        <td class="action-header">
                                                            <a class="btn btn-downgrade">
                                                                Downgrade
                                                            </a>
                                                        </td>
                                                        <td class="action-header">
                                                            <a class="btn btn-downgrade">
                                                                Downgrade
                                                            </a>
                                                        </td>
                                                        <td class="action-header">
                                                            <div class="current-plan">
                                                                <div class="with-date">Current Plan</div>
                                                                <div><em class="smaller block">renews Feb 19, 2015</em>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="action-header">
                                                            <a class="btn btn-upgrade">
                                                                Upgrade
                                                            </a>
                                                        </td>
                                                        <td class="action-header">
                                                            <a class="btn btn-downgrade">
                                                                Upgrade
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Website profile and business listing:</td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Online meeting integration:</td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Everyone starts here until onboard then invited to get verified:</td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Online Booking & payment system:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Policy & procedure templates:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Governance checks & professional verification level 2 to include DBS:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Clinical Recording:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Monthly online group CPD/webinar:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Can accept Referrals through MS:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Key business task reminders:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Joint commissioning:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Contract & Customer Management:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Governance checks & professional verification:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tender ready verification:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Referrals:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Bespoke reports & routine outcome measures:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Resources for clinical supervision :</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dedicated practice manager / Executive Virtual Assistant:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Full governance and recommendation:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Leadership/Community franchise opportunities:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tender/Grant applications:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Book-keeping:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Customer services:</td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-no fas fa-times"></i>
                                                        </td>
                                                        <td><i class="icon-yes fas fa-check"></i>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 p-0">
                                    <!-- header-responsive-view -->
                                    <div class="header-responsive-view">

                                        <ul class="list-group list-inline flex-sm-row">
                                            <li>
                                                <a href="">
                                                    <button><i class="fas fa-user-plus"></i></button>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="">
                                                    <button><img src="images/message-noti2.png" alt=""></button>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="">
                                                    <button><i class="fas fa-bell"></i></button>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="">
                                                    <button><i class="fas fa-cog setting-icon-top"></i></button>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="">
                                                    <button><img src="images/search-icon.png" alt=""></button>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!--end header-responsive-view -->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end container-fluid -->



        <!------------------- Add Service Modal  ----------------------->
        <div class="modal fade" id="serviceModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content modal-content-scroll">
                    <div class="modal-body">
                        <h4 class="modal-title profile-title-modal p-2">Add Service</h4>
                        <div class="step-message-modal">
                            <p><span>✔</span>Service Details</p>
                        </div>
                        <button type="button" class="close close-custom" data-dismiss="modal" aria-label="Close"> <i class="fas fa-arrow-left"></i> </button>
                        <section class="multistep-area pt-5">
                            <div class="col-md-9 mt-2 pt-1 pl-0">
                                <label class="pl-3 mb-3 modal-label-title">Service Information</label>

                                <div class="col-auto input-login-area">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user map-icon-modal"></i>
                                            </div>
                                        </div>
                                        <input id="service-title" class="form-control" type="text" name="" placeholder="Service Title">
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                                <div class="clearfix"></div>

                                <div class="col-auto input-login-area">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-dollar-sign map-icon-modal"></i>
                                            </div>
                                        </div>
                                        <input id="service-cost" class="form-control" type="text" name="" placeholder="Cost" onkeypress="return isNumber(event)">
                                    </div>
                                </div>

                                <label class="pl-3 mb-3 modal-label-title">Service Type</label>
                                <div class="col-auto input-login-area">
                                    <div class="input-group mb-2 select-appearance">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-user-alt"></i></div>
                                        </div>
                                        <select class="form-control" id="spinner-service-type">
                                            <option value="">Select Service Type</option>
                                            <option value="session">Session</option>
                                            <option value="meeting">Meeting</option>
                                            <option value="training">Training</option>
                                            <option value="group">Group</option>
                                            <option value="individual">Individual</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-auto input-login-area">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-clipboard map-icon-modal"></i>
                                            </div>
                                        </div>
                                        <input id="service-desc" class="form-control" type="text" name="" placeholder="Description">
                                    </div>
                                </div>

                                <div class="col-auto input-login-area">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-business-time map-icon-modal"></i>
                                            </div>
                                        </div>
                                        <input id="duration" class="form-control" type="text" name="" placeholder="Duration (6 Months)">
                                    </div>
                                </div>

                                <div class="col-md-12 modal-button-center">
                                    <button type="button" id="btn-save-services-info" class="mt-2 float-right action-button btn-success btn-next-all-btn mr-4">Save</button>
                                </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <!-- bootstrap -->
    <script src="js/jquery-3.2.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- custom.js -->
    <script src="js/custom.js"></script>
    <script src="js/toastr.js"></script>
    <script src="js/auth.js"></script>
    <script src="js/dashboard.js"></script>
    <script>
        var serviceTitle = "";
        var serviceDesc = "";
        var serviceCost = "";
        var serviceType = "";
        var serviceDuration = "";

        $("#btn-save-services-info").click(function(e) {
            serviceTitle = $("#service-title").val();
            serviceDesc = $("#service-desc").val();
            serviceCost = $("#service-cost").val();
            serviceDuration = $("#duration").val();
            serviceType = $("#spinner-service-type :selected").text();

            if (serviceTitle == "") {
                toastr.warning("Service title must not be empty!");
            } else if (serviceCost == "") {
                toastr.warning("Please specify service charges!");
            } else if (serviceType == "Select Service Type") {
                toastr.warning("Please select service type!");
            } else if (serviceDesc == "") {
                toastr.warning("Service description must not be empty!");
            } else if (serviceDuration == "") {
                toastr.warning("Service duration must not be empty!");
            } else {
                saveServices();
            }
        });

        //Log out
        function logout() {
            var sessionData = {
                Data: {
                    email: "",
                    lvl: "",
                    act: 2,
                },
            };

            var request = $.ajax({
                url: "services/api/session.php",
                type: "POST",
                data: sessionData,
                dataType: "html",
            });

            request.done(function(msg) {
                document.location.href = "login1.html";
                document.location.href = url;
            });

            request.fail(function(jqXHR, textStatus) {
                console.log(textStatus);
            });
        }

        function saveServices() {
            var servicesPayload = {
                Data: {
                    serviceTitle: serviceTitle,
                    serviceDesc: serviceDesc,
                    serviceDuration: serviceDuration,
                    serviceCost: serviceCost,
                    serviceType: serviceType,
                    lvl: 19
                },
            };

            var request = $.ajax({
                url: "services/api/profile.php",
                type: "POST",
                data: servicesPayload,
                dataType: "html"
            });

            request.done(function(msg) {
                var response = JSON.parse(jQuery.trim(msg));
                if (response.code == "00") {
                    toastr.success(response.desc);
                    $("#serviceModal").modal("hide");
                    location.reload();
                } else {
                    toastr.error(response.desc);
                }
            });

            request.fail(function(jqXHR, textStatus) {
                toastr.error(textStatus);
            });
        }

        //Get all Therapists
        function getAllBookings() {
            var bookingPayload = {
                Data: {
                    lvl: 20
                },
            };

            var request = $.ajax({
                url: "services/api/profile.php",
                type: "POST",
                data: bookingPayload,
                dataType: "html",
            });

            request.done(function(msg) {
                var response = JSON.parse(jQuery.trim(msg));
                if (response.code == "00") {
                    var a = response.data['services'];
                    var parent = $(".bookings-list");
                    if (a.length === 0) {
                        parent.append("<h5'>No services are listed yet! </h5>.");
                        return;
                    }
                    for (var i = 0; i < a.length; i++) {
                        console.log(a[i]['id']);
                        var email = a[i]['email'];
                        var therapistPicPath = a[i]['therapistEmail'];
                        if (doesFileExist("services/images/profile_pictures/" + therapistPicPath + ".png")) {
                            therapistPicPath = "user-avatar";
                        }
                        parent.append(`
                                    <div class="col-lg-3 col-sm-6 pl-0">
                                        <div class="profile-block-all">
                                            <img class="supervisor-profile-pic" src=services/images/profile_pictures/${therapistPicPath}.png alt = "" >

                                            <div class="supervisor-profile-block text-center">
                                                <h4>` + a[i]['serviceTitle'] + `</h4>
                                                <p>Physical Therapist & Supervisor</p>
                                                <p class="mt-2 pt-1"> Cost: £` + a[i]['serviceCost'] + `</p>
                                                <p class="pt-1">Duration: ` + a[i]['serviceDuration'] + `</p>
                                                <p class="pt-1 text-booking-status">` + a[i]['serviceType'] + `</p>
                                            </div>

                                            <div class="col-md-12 col-sm-12 text-center pl-0 pr-0">
                                        <a href="service-details.html?s_id=` + a[i]['id'] + `"><button
                                                class="btn btn-success nominate-btn btn-sen-message mb-2">View
                                                Service Details</button></a>
                                    </div>

                                        </div>
                                    </div>
                        `);
                    }
                }
            });

            request.fail(function(jqXHR, textStatus) {
                console.log(textStatus);
            });
        }

        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && charCode < 48) || charCode > 57) {
                return false;
            }
            return true;
        }

        //Check if session is logged in
        $(document).ready(function() {
            $("#vertical-nav-tabs").load("navigation.html");
            $(".top-filter-area").load("top-nav-bar.html");
            $(".dasboard-top-header").load("search-bar-navigation.html");

            var sessionData = {
                Data: {
                    email: "",
                    lvl: "",
                    act: 3,
                },
            };

            var request = $.ajax({
                url: "services/api/session.php",
                type: "POST",
                data: sessionData,
                dataType: "html",
            });

            request.done(function(msg) {
                if (msg == "") {
                    document.location.href = "login1.html";
                    document.location.href = url;
                } else {
                    getAllBookings();
                }
            });

            request.fail(function(jqXHR, textStatus) {
                console.log(textStatus);
            });
        });

        function doesFileExist(urlToFile) {
            var xhr = new XMLHttpRequest();
            xhr.open('HEAD', urlToFile, false);
            xhr.send();

            return xhr.status === 404;
        }

        $("#search-icon").click(function(e) {
            window.open("search-therapist.html?query=" + $(".input-search").val(), '_blank');
        });

        $(".input-search").on('keyup', function(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                window.open("search-therapist.html?query=" + $(".input-search").val(), '_blank');
            }
        });
    </script>
</body>

</html>