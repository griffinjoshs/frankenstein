<?php
include_once "includes/config.php";
if ((int)USERID > 0)
{
		header('Location: index.php');
}
include("header.php");
?>
  <div class="login-container container">
    <div class="tab-content">
      <div id="login" class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
        <form id="loginForm" class="log-in-form" action='/index.php' method='POST'> 
        <!-- <form id="loginForm" class="log-in-form" action='index.php' method='POST'>  -->
          <input name="login" type="hidden" value="true">
          <input class="timezone" name="timezone" type="hidden" value="">
          <!-- Email input -->
          <div class="form-outline mb-4">
            <input type="email" id="loginName" class="form-control" name='loginName'/>
            <label class="form-label" for="loginName">Email or username</label>
          </div>

          <!-- Password input -->
          <div class="form-outline mb-4">
            <input type="password" id="loginPassword" class="form-control" name='loginPassword' />
            <label class="form-label" for="loginPassword">Password</label>
          </div>
          <!-- 2 column grid layout -->
          <div class="row mb-4">
            <!-- <div class="col-md-6 d-flex justify-content-center"> -->
              <!-- Checkbox -->
              <!-- TODO: decide if you want this -->
              <!-- <div class="form-check mb-3 mb-md-0">
                <input class="form-check-input" type="checkbox" value="true" id="loginCheck" name='loginCheck' />
                <label class="form-check-label" for="loginCheck"> Remember me </label>
              </div> -->
            <!-- </div> -->

            <!-- <div class="col-md-6 d-flex justify-content-center"> -->
              <!-- Simple link -->
              <!-- <a href="#!">Forgot password?</a>
            </div> -->
          </div>
          <!-- Submit button -->
          <button id="loginSubmit" type="submit" class="btn btn-primary btn-block login-submit frankenstein-btn">Sign in</button>
          <!-- Register buttons -->
          <!-- <div class="text-center">
            <p>Not a member? <a href="#!" id='registerLink'>Register</a></p>
          </div> -->
        </form>
      </div>

      <div id="register" class="tab-pane fade" id="pills-register" role="tabpanel" aria-labelledby="tab-register">
        <form id="registerForm" class="log-in-form" action='/index.php' method='POST'>
        <!-- <form id="registerForm" class="log-in-form" action='index.php' method='POST'> -->
        <input name="register" type="hidden" value="true">
        <input class="timezone" name="timezone" type="hidden" value="">
          <!-- Name input -->
          <div class="form-outline mb-4">
            <input type="text" id="registerName" class="form-control" name='registerName'/>
            <label class="form-label" for="registerName">Name</label>
          </div>

          <!-- Username input -->
          <div class="form-outline mb-4">
            <input type="text" id="registerUsername" class="form-control" name='registerUsername'/>
            <label class="form-label" for="registerUsername">Username</label>
          </div>

          <!-- Email input -->
          <div class="form-outline mb-4">
            <input type="email" id="registerEmail" class="form-control" name='registerEmail'/>
            <label class="form-label" for="registerEmail">Email</label>
          </div>

          <!-- Password input -->
          <div class="form-outline mb-4">
            <input type="password" id="registerPassword" class="form-control" name='registerPassword'/>
            <label class="form-label" for="registerPassword">Password</label>
          </div>

          <!-- Repeat Password input -->
          <div class="form-outline mb-4">
            <input type="password" id="registerRepeatPassword" class="form-control" name='registerRepeatPassword'/>
            <label class="form-label" for="registerRepeatPassword">Repeat password</label>
          </div>

          <!-- Checkbox -->
          <div class="form-check d-flex justify-content-center mb-4">
            <input class="form-check-input me-2" type="checkbox" value="" id="registerCheck" name='registerCheck' checked
              aria-describedby="registerCheckHelpText" />
            <label class="form-check-label" for="registerCheck">
              I have read and agree to the terms
            </label>
          </div>

          <!-- Submit button -->
          <button id="registerSubmit" type="submit" class="btn btn-primary btn-block login-submit frankenstein-btn">Sign in</button>
        </form>
      </div>
    </div>
<!-- 
    <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
      <li class="nav-item" role="presentation">
        <a class="nav-link active" id="tab-login" data-mdb-toggle="pill" role="tab"
          aria-controls="pills-login" aria-selected="true">Login</a>
      </li>
      <li class="nav-item" role="presentation">
        <a class="nav-link" id="tab-register" data-mdb-toggle="pill" role="tab"
          aria-controls="pills-register" aria-selected="false">Register</a>
      </li>
    </ul> -->
    
  </div> <!-- Login Container -->
  <?php include("footer.php")?>
  <?php if(isset($error)) { echo $error; } ?>

  <script>
    sizeContainerToWindow($(".login-container"));
    sizeContainerToWindow($("#preloader"));
    $(document).ready(function() {
      closeGenericLoader();
      $(".timezone").val(Intl.DateTimeFormat().resolvedOptions().timeZone);
      $(".login-submit").on("click", function(e) {
        e.preventDefault();
        let thisForm = $(this).parentsUntil("log-in-form"),
        thisFormType = thisForm.attr("id").replace("Form", ""),
        thisFormValues = thisForm.serializeArray(),
        v = validateForm(thisFormValues);
        if(v == true) {
          // submitLoginForm(e, thisFormValues);
          thisForm.submit();
        } else {
          // console.log(v);
          let missingList = '';
          $.each(v, function(i, item) {
            $("#" + item).addClass("required-input");
            if(i + 1 == v.length) {
              missingList += item.replace('login', '');
            } else {
              missingList += item.replace('login', '') + ', ';
            }
            
          });
          systemMessageSlideIn('error', 'Missing Fields', 'The following fields: <strong>' + missingList + '</strong> are blank. Please fill out all required fields and try again.', 4000, 500)
        }
      })
    });

  // not using
  function submitLoginForm(e, formValues) {
    e.preventDefault();
    var timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    let data = {
      'login': formValues[0]['value'],
      'loginName': formValues[1]['value'],
      'loginPassword' : formValues[2]['value'],
      'timezone' : timezone
    }
    console.log(data);
    $.ajax({
        // 'url':'<?=BASE_URL?>/login.php',
        // 'url':'login.php',
        'data' : data,
        'type':'POST',
        contentType: "application/json; charset=utf-8",
        'dataType': 'json',
        'success':function(response){
            console.log(response, "response");
            if(response != '') {
                systemMessageSlideIn('error', 'Incorrect Username / Password', response, 3000, 500);
            } else {
              window.location.reload();
            }
            // console.log(response, "response");
        }, 'error':function(err) {
            console.log("err", err);
        }
	});
  }
  </script>
  </body>
</html>