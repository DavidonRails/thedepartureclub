<!doctype html>
<html lang="en">
<head>
    <base href="{{ url() }}">
    <title>Departure Club</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,300,700,900">
    @if(Config::get('app.debug'))
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('build/css/app.css') }}">
    @endif
    <!-- FONTS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <script>
        var fbid = '{{getenv('FACEBOOK_APP_ID')}}'
    </script>
</head>
<body ng-app="app" ng-controller="AppController">
    <div id="pagePreload">
        <div id="status">&nbsp;</div>
    </div>


    <section class="app-terms">
        <article>
            <h5>Terms & Conditions</h5>
            <ul>
				<li><strong>TAXES:</strong> Every Hobo Jet flight includes Federal Excise Tax of 7.5% and a Segment Tax of $4 per person</li>
				<li><strong>LIABILITY:</strong> Customer acknowledges Hobo Jet does not own or operate any aircraft. Hobo Jet arranges flights on behalf of our clients with FAR Part 135 air carriers that exercise full operational control of each flight.</li>
				<li><strong>BOOKING:</strong> Flights aren’t confirmed until the flight is paid and Hobo Jet has received approval from the operator.</li>
				<li><strong>CANCELLATION BY HOBO JET:</strong> In the event the flight is cancelled by Hobo Jet, the Customer will receive 100% of the paid fare amount in a form of a credit to the credit card account which was used to purchase the flight. The decision to cancel a flight will be at Hobo Jet’s sole discretion and in the event Hobo Jet shall incur no liability other than the full refund of the fare paid.</li>
				<li><strong>LATE PASSENGER ARRIVAL:</strong> All passengers must arrive 20 minutes prior to the agreed departure time of the flight. Failure to arrive within this timeframe will subject the flight to cancellation at Hobo Jet’s sole discretion.</li>
				<li><strong>CHANGE OF DEPARTURE TIME:</strong> Departure time of flight cannot be changed.</li>
                <li><strong>NAME CHANGES:</strong> Name changes are not allowed.</li>
                <li><strong>ADDITIONAL PASSENGERS:</strong> Only Customers whose names appear on the confirmation may travel on the flight. Individuals whose names do not appear on the confirmation will be denied access to the aircraft.</li>
                <li><strong>PET POLICY:</strong> Pets are not allowed.</li>
                <li><strong>REFUNDS FOR CUSTOMER CANCELLATION OR NO SHOW:</strong> Hobo Jet flights are 100% non-refundable and cannot be cancelled. No cash refunds in any amount will be granted for any reason at any time.</li>
                <li><strong>ADDITIONAL CHARGES:</strong> Customer agrees that Hobo Jet may charge Customer’s credit card for any and all damage and excess wear & tear caused to the aircraft by Customer during the Customer’s flight as determined at Hobo Jet’s sole discretion.</li>
                <li><strong>OPERATIONAL:</strong> Smoking in aircraft is not permitted. Hazardous materials are not accepted on board or in checked baggage. Each Customer 18 years of age or older will be required to show a valid government-issued photo ID that must match the name on the confirmation. Customers under 18 years of age will not be required to show an ID, but their name(s) must appear on the reservation. One lap child per flight under the age of 2 is permitted, although weight restrictions may apply to any flight.</li>
			</ul>
        </article>
    </section>





    @if(Config::get('app.debug'))
        <script src="{{ asset('js/dependencies.js') }}"></script>
    @else
        <script src="{{ asset('build/js/dependencies.js') }}"></script>
    @endif

    @if(Config::get('app.debug'))
        <script src="{{ asset('js/app/app.js') }}"></script>
    @else
        <script src="{{ asset('build/js/app.js') }}"></script>
    @endif
</body>
</html>
