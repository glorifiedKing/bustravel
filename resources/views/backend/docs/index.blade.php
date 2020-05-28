@extends('bustravel::backend.docs.layout')

@section('title', 'PalmKash User Manual')



@section('content')
        <h4 id="account_login" class="mb-4">User account login</h4>
        <p>Related links: <a href="#change_my_password">Change your password, </a><a href="#sign_me_out">Sign out of you account</a></p>
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
                <p>
                  <ol>
                    <li>Begin by navigating to the web address; <a href="https://transport.palmkash.com" target="_blank" rel="noopener noreferrer">https://transport.palmkash.com</a></li>
                    <li>On the righthand side of the page, click the link labelled <strong>"Your Account"</strong>. <br>A drop
                    down list will appear with 2 options, Login and Register as shown in the screenshot. Select Login.</li>
                    <li>On the next page, type in your user email and password then click the <strong>"Login"</strong> button.</li>
                    <li>In case you forgot password, follow the link provided.</li>
                  </ol>
                </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
              <h6>Login link screenshot</h6>
              <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/login-drop-down-link.png') }}"/>
            </div>
          </div>
        </div>

        <div class="container-fluid">
          <h4 id="change_my_password" class="mb-4 mt-5 pt-5">How to change your password</h4>
          <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
                <p>
                  <ol>
                    <li>Begin by navigating to the web address; <a href="https://transport.palmkash.com" target="_blank" rel="noopener noreferrer">https://transport.palmkash.com</a></li>
                    <li>Log into you account using your active user email and password.</li>
                    <li>After you are logged select the <strong>"User and Profile"</strong> link in the left sidebar menu and select <strong>"Profile"</strong>.</li>
                    <li>Enter your new password (should be a minimum of eight characters) in both fields and save/submit.</li>
                    <li>You should now be able to log in with your new password.</li>
                  </ol>
                </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
              <h6>Change password screenshot</h6>
              <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/change-password.png') }}"/>
            </div>
          </div>
        </div>

        <div class="container-fluid">
          <h4 id="sign_me_out" class="mb-4 mt-5 pt-5">How to log out of your account</h4>
          <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-6">
                <p>
                  <ol>
                    <li>While logged into your account, in the top right hand corner of the user dashboard. Click your Username.</li>
                    <li>In the dropdown menu that appears, select/click the Log out option.</li>
                    <li>This will successfully log you out of your account</li>
                  </ol>
                </p>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mt-4">
              <h6>Login link screenshot</h6>
              <img class="demo_image" src="{{asset('vendor/glorifiedking/docs/images/logout-link.png') }}"/>
            </div>
          </div>
        </div>

@stop
