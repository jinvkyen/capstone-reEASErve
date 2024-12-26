<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar {{$cms['dept_label']}}</title>
    <!-- .ico logo -->
    <link rel="icon" href="{{ asset('storage/icon-logo/logo.ico') }}" type="image/x-icon">

    <!-- Tailwind -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="{{ asset('css/sweetalert2.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/swal.min.js') }}"></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <!-- Calendar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
</head>

<body class="bg-white font-mont">
    <div class="flex flex-col lg:flex-row">
        @include('login.users.sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1 lg:ml-80 my-4">
            <!-- Title -->
            <div class="header flex justify-between ml-4 lg:ml-8">
                <h1 class="mt-5 lg:text-4xl font-bold sm:text-2xl">Reservation Calendar</h1>
                <!-- Profile -->
                <div class="sticky top-0 ml-4 lg:ml-8 sm:ml-3">
                    @include('login.users.profile-card')
                </div>

            </div>

            <div class="flex flex-col p-4 rounded-lg shadow-lg mx-4 mt-8 font-mont" style="background-color: var(--custom-color);">
                <div class="bg-white p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
                    <div id="calendar" class="font-mont text-gray-800"></div>
                </div>
            </div>
        </div>

        <!-- Slide-in panel for event details -->
        <div id="eventDetailsPanel" class="fixed top-0 right-0 h-full w-0 overflow-x-hidden transition-width duration-500 ease-in-out shadow-lg z-30" style="background-color: var(--custom-color);">
            <span class="z-30 absolute top-4 right-4 text-2xl cursor-pointer" id="closePanel">&times;</span>
            <div id="eventDate" class="z-30 text-center text-black mt-16 text-2xl font-bold"></div>

            <div class="z-30 bg-white p-6 mx-6 mt-6 shadow-md rounded-lg">
                <div id="eventDetails">
                    <div class="z-30 bg-gray-200 p-4 my-2 rounded text-center">
                        <span id="eventTime"></span> | <span id="eventTitle"></span>
                    </div>
                </div>
            </div>
        </div>

        <div id="overlay" class="fixed inset-0 bg-black opacity-50 z-20 hidden"></div>

        <!-- Color per Resource Reserved -->
        <?php
        function getLightColor($resourceId)
        {
            $hash = md5($resourceId);
            $number = hexdec(substr($hash, 0, 6));
            $r = ($number & 0xFF0000) >> 16;
            $g = ($number & 0x00FF00) >> 8;
            $b = $number & 0x0000FF;
            $r = ($r + 255) / 2;
            $g = ($g + 255) / 2;
            $b = ($b + 255) / 2;
            return sprintf('#%02X%02X%02X', $r, $g, $b);
        } ?>

        <script>
            $(document).ready(function() {
                var calendar = $('#calendar').fullCalendar({
                    aspectRatio: 1.15,
                    height: 'auto',
                    contentHeight: 'auto',
                    editable: true,
                    header: {
                        left: 'prev,next,today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    eventRender: function(event, element) {
                        element.css('background-color', event.color);
                        element.css('border-color', event.color);
                        element.find('.fc-event-title').css('white-space', 'normal');

                    },
                    events: [
                        <?php foreach ($reserved_items as $reserved_item) : ?> {
                                title: '<?php echo $reserved_item->resource_name; ?>',
                                start: '<?php echo $reserved_item->start; ?>',
                                end: '<?php echo $reserved_item->end; ?>',
                                status: '<?php echo $reserved_item->status; ?>',
                                color: '<?php echo getLightColor($reserved_item->resource_id); ?>',
                                type: '<?php echo isset($reserved_item->type) ? $reserved_item->type : 'Resource'; ?>',
                                first_name: '<?php echo $reserved_item->first_name; ?>',
                                last_name: '<?php echo $reserved_item->last_name; ?>'
                            },
                        <?php endforeach; ?>
                    ],
                    eventClick: function(event) {
                        var eventData = {
                            Title: event.title,
                            StartDate: event.start.format("MMMM D, YYYY"),
                            StartTime: event.start.format("hh:mm A"),
                            EndTime: event.end.format("hh:mm A"),
                            Status: event.status,
                            Type: event.type,
                            FirstName: event.first_name,
                            LastName: event.last_name
                        };

                        // Clear previous content
                        $("#eventDetails").empty();

                        // Create and append new elements
                        $("#eventDate").html("<span class='font-mont'><strong>" + eventData.StartDate + "</strong></span>");

                        // User
                        $("#eventReserver").text(eventData.FirstName + " " + eventData.LastName);

                        var detailsHtml = `<div class="bg-gray-100 p-4 rounded-lg shadow-md font-mont">
                            <h3 class="text-xl font-bold mb-3">${eventData.Title}</h3>
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>${eventData.StartTime} - ${eventData.EndTime}</span>
                            </div>
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                <span>Status: ${eventData.Status}</span>
                            </div>
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                <span>Type: ${eventData.Type}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16"><path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/><path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/></svg>                                
                                <span>User: ${eventData.LastName}  ${eventData.FirstName}</span>
                            </div>
                        </div>`;

                        $("#eventDetails").html(detailsHtml);

                        // Show the panel with a smooth transition
                        $("#eventDetailsPanel").css({
                            "width": "100%",
                            "max-width": "400px",
                            "box-shadow": "0 0 15px rgba(0,0,0,0.1)"
                        }).addClass('sm:w-1/2 lg:w-1/3');

                        $("#overlay").fadeIn(300);
                        $('body').addClass('overlay-active');
                    }
                });

                $("#eventDetailsPanel .close, #overlay").click(function() {
                    $("#eventDetailsPanel").css("width", "0");
                    $("#overlay").hide();
                    $('body').removeClass('overlay-active');
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                var closeButton = document.getElementById('closePanel');
                var overlay = document.getElementById('overlay');

                closeButton.addEventListener('click', function() {
                    document.getElementById('eventDetailsPanel').style.width = '0';
                    overlay.style.display = 'none';
                    document.body.classList.remove('overlay-active');
                });

                overlay.addEventListener('click', function() {
                    document.getElementById('eventDetailsPanel').style.width = '0';
                    overlay.style.display = 'none';
                    document.body.classList.remove('overlay-active');
                });
            });
        </script>
    </div>
</body>
<style>
    /* Custom styles for the calendar */
    #calendar .fc-title {
        font-size: 0.66rem;
        font-family: 'Montserrat', sans-serif;
    }

    #calendar .fc-time {
        font-size: 0.75rem;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
    }

    #calendar .fc-day-number {
        font-size: 0.8rem;
        font-family: 'Montserrat', sans-serif;
    }

    .fc-toolbar .fc-center h2 {
        margin-top: 1rem;
        margin-bottom: 0px;
        font-size: 24px;
        font-weight: bold;
    }

    .fc-widget-header .fc-day-header {
        font-size: 0.53rem;
        font-family: 'Montserrat', sans-serif;
        font-weight: 600;
    }
</style>
</html>