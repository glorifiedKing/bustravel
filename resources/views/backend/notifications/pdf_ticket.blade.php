<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Transport.PalmKash.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css" rel="stylesheet" media="all">
        /* Media Queries */
        @media only screen and (max-width: 500px) {
            .button {
            width: 100% !important;
            }
        }
        body,
        p,
        h1 {
        margin: 0;
        padding: 0;
        font-family: "Poppins", sans-serif;
        }

        .container {
        background: #e0e2e8;
        position: relative;
        width: 100%;
        height: 100vh;
        }
        .container .ticket {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        }
        .container .basic {
        display: none;
        }
        .airline {
        display: block;
        height: 975px;
        width: 600px;
        background: #283e5d;
        box-shadow: 5px 5px 30px rgba(0, 0, 0, 0.3);
        border-radius: 25px;
        z-index: 3;
        }
        .airline .top {
        height: 300px;
        background: #f6f6fc;
        border-top-right-radius: 25px;
        border-top-left-radius: 25px;
        }
        .airline .top h1 {
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 2;
        text-align: center;
        position: absolute;
        top: 30px;
        left: 50%;
        transform: translateX(-50%);
        }
        .airline .bottom {
        height: 595px;
        background: #fff;
        border-bottom-right-radius: 25px;
        border-bottom-left-radius: 15px;
        }

        .top .big {
        /* position: absolute; */
        top: 40px;
        /* font-size: 65px; */
        /* font-weight: 700; */
        line-height: 0.8;
        /* left: 200px; */
        }
        .top .big .from {
        color: #0872a6;
        text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
        }
        .top .big .to {
        position: absolute;
        left: 52px;
        font-size: 35px;
        display: flex;
        flex-direction: row;
        align-items: center;
        }
        .top .big .to i {
        margin-top: 10px;
        margin-right: 30px;
        font-size: 15px;
        }
        .top--side {
        position: absolute;
        right: 55px;
        top: 110px;
        text-align: right;
        }
        .top--side i {
        font-size: 25px;
        margin-bottom: 28px;
        }
        .top--side p {
        font-size: 10px;
        font-weight: 700;
        }
        .top--side p + p {
        margin-bottom: 10px;
        }

        .top p {
        display: flex;
        flex-direction: column;
        font-size: 20px;
        font-weight: 700;
        }
        .top span {
        font-weight: 100;
        font-size: 18px;
        color: #0872a6;
        }
        .top .column {
        margin: 0 auto;
        width: 80%;
        padding: 1rem 0;
        }
        .top .row {
        display: flex;
        margin-top: 10px;
        justify-content: space-between;
        }
        .top .row--right {
        text-align: right;
        }
        .top .row--center {
        text-align: center;
        }
        .bottom p {
        display: flex;
        flex-direction: column;
        font-size: 17px;
        font-weight: 600;
        }
        .bottom span {
        font-weight: 400;
        font-size: 15px;
        color: #0872a6;
        }
        .bottom .column {
        margin: 0 auto;
        width: 80%;
        padding: 1rem 0;
        }
        .bottom .row {
        display: flex;
        margin-top: 10px;
        /* justify-content: space-between; */
        }
        .bottom .row--right {
        text-align: right;
        }
        .bottom .row--center {
        text-align: center;
        }
        .bottom .row-2 {
        margin: 0px 0 40px 0;
        position: relative;
        }
        .bottom .row-2::after {
        content: "";
        position: absolute;
        width: 100%;
        bottom: -20px;
        left: 0;
        background: #000;
        height: 3px;
        }

        .bottom .bar--code {
        height: 80px;
        width: 80%;
        margin: 0 auto;
        position: relative;
        }
        .bottom .bar--code::after {
        content: "";
        position: absolute;
        width: 6px;
        height: 100%;
        background: #000;
        top: 0;
        left: 0;
        box-shadow: 10px 0 #000, 30px 0 #000, 40px 0 #000, 67px 0 #000, 90px 0 #000, 100px 0 #000, 180px 0 #000, 165px 0 #000, 200px 0 #000, 210px 0 #000, 135px 0 #000, 120px 0 #000;
        }
        .bottom .bar--code::before {
        content: "";
        position: absolute;
        width: 3px;
        height: 100%;
        background: #000;
        top: 0;
        left: 31px;
        box-shadow: 12px 0 #000, -4px 0 #000, 45px 0 #000, 65px 0 #000, 72px 0 #000, 78px 0 #000, 97px 0 #000, 150px 0 #000, 165px 0 #000, 180px 0 #000, 135px 0 #000, 120px 0 #000;
        }

        .info {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        bottom: 10px;
        font-size: 14px;
        text-align: center;
        z-index: 1;
        }

        .date {
            border-top: 1px solid #fccc04;
            border-bottom: 1px solid #fccc04;
            padding: 2px 0;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .date span {
            width: 100px;
        }

        .date span:first-child {
            text-align: left;
        }

        .date span:last-child {
            text-align: right;
        }

        .date .month {
            color: #d83565;
            font-size: 20px;
        }
    </style>
    
</head>
<body>
    <div class="container">
        <div class="ticket airline">
            <div class="top" style="margin-left: 70px;">
                <div class="column">
                    <div class="row">
                        <div class="col-md-6"> 
                            <!-- <img src="https://freesvg.org/img/1286146771.png" style="text-align: center; margin-bottom: 20px;  width: 100px; height: 50px;" /> -->
                        </div>
                        <div class="col-md-6">
                            <table style="width:100%;">
                                <tr>
                                    <td><span class="col-md-12">Ticket No:</span></td>
                                </tr>
                                <tr>
                                    <td style="text-align:left"><p>{{ $ticket_number }}</p></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="big" style="text-align: center;">
                                <img src="data:image/png;base64, {!! base64_encode(QrCode::size(220)->generate($ticket_number)) !!} " style="text-align: center; margin-bottom: 20px;  width: 180px; height: 180px;">
                            </div>
                        </div>
                       
                    </div>
                </div>           
            </div>
            <div class="bottom" style="margin-left: 70px;">
                <div class="column">
                    <div class="row">
                        <p>Boarding Pass</p>
                    </div>
                    <div class="row">
                        
                    </div>

                    <div class="row">
                        <table style="width:100%;">
                            <tr>
                                <td><span class="col-md-12">Name:</span></td>
                                <td style="text-align:right"><span>Ticket Type:</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left"><p>{{ $name }} </p></td>
                                <td style="text-align:right"><p> </p></td>
                            </tr>
                        </table>
                    </div>

                    <div class="row">
                        <table style="width:100%;">
                            <tr>
                                <td><span class="col-md-12">Departure:</span></td>
                                <td style="text-align:center"><span>Date:</span></td>
                                <td style="text-align:right"><span>Time:</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left"><p>{{ $departure_station }} </p></td>
                                <td style="text-align:center"><p>{{ $date_of_travel}}</p></td>
                                <td style="text-align:right"><p>{{ $destination_time}}</p></td>
                            </tr>
                        </table>
                    </div>

                    <div class="row">
                        <table style="width:100%;">
                            <tr>
                                <td><span class="col-md-12">Arrival:</span></td>
                                <td style="text-align:center"><span>Date:</span></td>
                                <td style="text-align:right"><span>Time:</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left"><p>{{ $arrival_station }}</p></td>
                                <td style="text-align:center"><p>{{ $date_of_travel}}</p></td>
                                <td style="text-align:right"><p>{{ $destination_time}}</p></td>
                            </tr>
                        </table>
                    </div>

                    <div class="row">
                        <table style="width:100%;">
                            <tr>
                                <td><span class="col-md-12">Date Paid:</span></td>
                                <td style="text-align:right"><span>Time:</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left"><p>{{ $date_paid }} </p></td>
                                <td style="text-align:right"><p>{{ $time_of_payment}}</p></td>
                            </tr>
                        </table>
                    </div>

                    <div class="row">
                        <table style="width:100%;">
                            <tr>
                                
                                <td><span class="col-md-12">Vehicle No:</span></td>
                                <td style="text-align:center"><span>Seat No:</span></td>
                                <td style="text-align:right"><span>Fare:</span></td>
                            </tr>
                            <tr>
                                <td style="text-align:left"><p></p></td>
                                <td style="text-align:center"><p>{{ $seat_number}}</p></td>
                                <td style="text-align:right"><p>{{ $ticket_price}}</p></td>
                            </tr>
                        </table>
                    </div>

                    <div class="row">
                
                    </div>

                    <div class="row" style="text-align:center; margin-top: 30px;">
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::size(220)->generate($ticket_number)) !!} " style="text-align: center; margin-bottom: 20px;  width: 100px; height: 100px;">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="info">
                    <p tyle="color: #fffff">
                        <b>
                                &copy; {{ date('Y') }} PalmKash.All rights reserved.
                        </b>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>