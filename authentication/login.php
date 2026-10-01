<?php



session_start();


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");


if (isset($_SESSION['id'])) {

  header("Location: ../dashboard.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0">

  <title>IMS Safety Management System</title>


  <!-- Bootstrap -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">


  <!-- Bootstrap Icons -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


  <style>
    /* =====================================================
           GLOBAL
        ====================================================== */

    html,
    body {
      height: 100%;
    }

    body {

      margin: 0;

      font-family:
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        Roboto,
        Helvetica,
        Arial,
        sans-serif;

      background:
        linear-gradient(90deg,
          rgba(3, 12, 24, .25),
          rgba(3, 12, 24, .05)),
        url("../assets/img/ims-safety-bg.png") center center / cover no-repeat fixed;

      color: #fff;

      transition:
        background .35s ease,
        color .35s ease;
    }



    .page-overlay {

      position: fixed;

      inset: 0;

      z-index: 0;

      background:
        linear-gradient(90deg,
          rgba(3, 10, 20, .48) 0%,
          rgba(3, 10, 20, .12) 55%,
          rgba(3, 10, 20, .18) 100%);
    }


    /* =====================================================
           WRAPPER
        ====================================================== */

    .login-wrapper {

      position: relative;

      z-index: 1;

      min-height: 100vh;
    }


    .brand-logo {

      font-size: 34px;

      font-weight: 800;

      letter-spacing: -1px;
    }

    .brand-logo i {

      color: #3b91ff;
    }

    .brand-subtitle {

      font-size: 13px;

      letter-spacing: .2px;

      color:
        rgba(255, 255, 255, .75);
    }



    .safety-label {

      font-size: 13px;

      letter-spacing: 4px;

      font-weight: 600;

      color:
        rgba(255, 255, 255, .85);
    }

    .hero-title {

      font-size:
        clamp(42px, 5vw, 72px);

      line-height: .98;

      font-weight: 750;

      letter-spacing: -3px;
    }

    .hero-title span {

      color: #006ee4;
    }

    .hero-description {

      max-width: 420px;

      color:
        rgba(255, 255, 255, .78);

      font-size: 17px;

      line-height: 1.55;
    }


    .benefit-box {

      background:
        rgba(8, 20, 35, .48);

      border:
        1px solid rgba(255, 255, 255, .13);

      backdrop-filter:
        blur(14px);

      -webkit-backdrop-filter:
        blur(14px);

      border-radius: 18px;
    }

    .benefit-icon {

      font-size: 23px;
    }

    .benefit-title {

      font-size: 12px;

      font-weight: 600;

      color: #fff;
    }



    .login-card {

      width:
        min(480px, 100%);

      background:
        rgba(20, 36, 55, .62);

      border:
        1px solid rgba(255, 255, 255, .20);

      border-radius: 30px;

      backdrop-filter:
        blur(25px);

      -webkit-backdrop-filter:
        blur(25px);

      box-shadow:
        0 30px 80px rgba(0, 0, 0, .35),

        inset 0 1px 0 rgba(255, 255, 255, .10);

      padding: 48px;
    }



    .login-logo {

      width: 68px;

      height: 68px;

      border-radius: 20px;

      display: flex;

      align-items: center;

      justify-content: center;

      margin:
        0 auto 14px;

      background:
        linear-gradient(145deg,
          #58a8ff,
          #1769e8);

      box-shadow:
        0 12px 30px rgba(36, 125, 255, .30);
    }

    .login-logo i {

      font-size: 36px;

      color: #fff;
    }


    .login-title {

      font-size: 31px;

      font-weight: 750;

      letter-spacing: -1px;
    }

    .login-subtitle {

      color:
        rgba(255, 255, 255, .68);

      font-size: 14px;
    }

    .login-line {

      width: 35px;

      height: 2px;

      background:
        rgba(255, 255, 255, .35);

      margin:
        18px auto 26px;
    }


    .input-group-custom {

      position: relative;
    }

    .input-icon {

      position: absolute;

      left: 20px;

      top: 50%;

      transform:
        translateY(-50%);

      z-index: 5;

      color:
        rgba(255, 255, 255, .72);

      font-size: 20px;
    }

    .login-input {

      height: 58px;

      padding-left: 55px;

      padding-right: 50px;

      border-radius: 17px;

      color: #fff !important;

      background:
        rgba(255, 255, 255, .075) !important;

      border:
        1px solid rgba(255, 255, 255, .18) !important;

      box-shadow: none !important;
    }

    .login-input::placeholder {

      color:
        rgba(255, 255, 255, .60);
    }

    .login-input:focus {

      border-color:
        rgba(72, 150, 255, .8) !important;

      background:
        rgba(255, 255, 255, .10) !important;

      box-shadow:
        0 0 0 4px rgba(59, 130, 246, .12) !important;
    }



    .password-toggle {

      position: absolute;

      right: 18px;

      top: 50%;

      transform:
        translateY(-50%);

      z-index: 5;

      border: none;

      background: transparent;

      color:
        rgba(255, 255, 255, .70);

      font-size: 19px;
    }

    .password-toggle:hover {

      color: #fff;
    }


    .login-error {

      min-height: 22px;

      margin:
        5px 4px 14px;

      color: #dc3545;

      font-size: 13px;

      font-weight: 500;

      opacity: 0;

      transform:
        translateY(-3px);

      transition:
        all .2s ease;
    }

    .login-error.show {

      opacity: 1;

      transform:
        translateY(0);
    }


    .btn-signin {

      height: 58px;

      border: none;

      border-radius: 17px;

      background:
        linear-gradient(135deg,
          #3d9bff,
          #1769e8);

      color: #fff;

      font-size: 16px;

      font-weight: 600;

      box-shadow:
        0 12px 28px rgba(31, 117, 240, .30);

      transition:
        .2s ease;
    }

    .btn-signin:hover {

      transform:
        translateY(-2px);

      background:
        linear-gradient(135deg,
          #51a5ff,
          #2477ee);

      box-shadow:
        0 16px 32px rgba(31, 117, 240, .40);
    }

    .btn-signin:active {

      transform:
        translateY(0);
    }



    .login-footer {

      color:
        rgba(255, 255, 255, .45);

      font-size: 11px;

      letter-spacing: .5px;
    }



    .theme-button {

      position: absolute;

      top: 28px;

      right: 30px;

      width: 42px;

      height: 42px;

      border-radius: 50%;

      border:
        1px solid rgba(255, 255, 255, .18);

      background:
        rgba(255, 255, 255, .08);

      color: #fff;

      display: flex;

      align-items: center;

      justify-content: center;

      backdrop-filter:
        blur(10px);

      cursor: pointer;
    }


    body.light-mode {

      background:
        linear-gradient(90deg,
          rgba(235, 242, 250, .75),
          rgba(245, 248, 252, .35)),
        url("../assets/img/ims-safety-bg.png") center center / cover no-repeat fixed;

      color: #172033;
    }

    body.light-mode .page-overlay {

      background:
        linear-gradient(90deg,
          rgba(255, 255, 255, .40),
          rgba(255, 255, 255, .15) 55%,
          rgba(255, 255, 255, .25));
    }

    body.light-mode .brand-subtitle {

      color:
        rgba(20, 35, 55, .65);
    }

    body.light-mode .safety-label {

      color:
        rgba(20, 35, 55, .70);
    }

    body.light-mode .hero-description {

      color:
        rgba(20, 35, 55, .70);
    }

    body.light-mode .benefit-box {

      background:
        rgba(255, 255, 255, .50);

      border-color:
        rgba(20, 35, 55, .10);
    }

    body.light-mode .benefit-title {

      color: #172033;
    }

    body.light-mode .login-card {

      background:
        rgba(255, 255, 255, .62);

      border-color:
        rgba(20, 35, 55, .12);

      box-shadow:
        0 30px 80px rgba(30, 60, 90, .18),

        inset 0 1px 0 rgba(255, 255, 255, .8);
    }

    body.light-mode .login-subtitle {

      color:
        rgba(20, 35, 55, .60);
    }

    body.light-mode .login-line {

      background:
        rgba(20, 35, 55, .20);
    }

    body.light-mode .text-white-50 {

      color:
        rgba(20, 35, 55, .55) !important;
    }

    body.light-mode .login-input {

      color:
        #172033 !important;

      background:
        rgba(255, 255, 255, .70) !important;

      border-color:
        rgba(20, 35, 55, .15) !important;
    }

    body.light-mode .login-input::placeholder {

      color:
        rgba(20, 35, 55, .50);
    }

    body.light-mode .input-icon {

      color:
        rgba(20, 35, 55, .60);
    }

    body.light-mode .password-toggle {

      color:
        rgba(20, 35, 55, .60);
    }

    body.light-mode .password-toggle:hover {

      color: #172033;
    }

    body.light-mode .theme-button {

      background:
        rgba(255, 255, 255, .65);

      border-color:
        rgba(20, 35, 55, .12);

      color: #172033;
    }

    body.light-mode .login-footer {

      color:
        rgba(20, 35, 55, .45);
    }



    .login-success-overlay {

      position: fixed;

      inset: 0;

      z-index: 9999;

      display: flex;

      align-items: center;

      justify-content: center;

      background:
        rgba(15, 23, 42, .35);

      backdrop-filter:
        blur(8px);

      -webkit-backdrop-filter:
        blur(8px);

      opacity: 0;

      visibility: hidden;

      transition:
        opacity .25s ease,
        visibility .25s ease;
    }

    .login-success-overlay.show {

      opacity: 1;

      visibility: visible;
    }

    .login-success-popup {

      width:
        min(380px,
          calc(100% - 40px));

      padding:
        34px 30px;

      text-align: center;

      background:
        rgba(255, 255, 255, .94);

      border:
        1px solid rgba(255, 255, 255, .8);

      border-radius: 26px;

      box-shadow:
        0 30px 80px rgba(15, 23, 42, .20);

      transform:
        scale(.92) translateY(10px);

      transition:
        transform .3s ease;
    }

    .login-success-overlay.show .login-success-popup {

      transform:
        scale(1) translateY(0);
    }

    .success-icon {

      width: 68px;

      height: 68px;

      margin:
        0 auto 18px;

      display: flex;

      align-items: center;

      justify-content: center;

      border-radius: 50%;

      background:
        #dcfce7;

      color:
        #16a34a;

      font-size: 32px;

      animation:
        successPop .45s ease;
    }

    @keyframes successPop {

      0% {
        transform: scale(.5);
        opacity: 0;
      }

      70% {
        transform: scale(1.08);
      }

      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    .login-success-popup h4 {

      margin-bottom: 7px;

      color: #172033;

      font-size: 21px;

      font-weight: 700;
    }

    .login-success-popup p {

      margin-bottom: 20px;

      color: #64748b;

      font-size: 14px;
    }

    .success-loader {

      display: flex;

      align-items: center;

      justify-content: center;

      gap: 9px;

      color: #64748b;

      font-size: 13px;
    }

    .success-loader .spinner-border {

      width: 15px;

      height: 15px;

      color: #2563eb;
    }


    /* =====================================================
           RESPONSIVE
        ====================================================== */

    @media (max-width: 991.98px) {

      body {
        background-position: center;
      }

      .login-wrapper {
        padding: 35px 20px;
      }

      .left-content {

        text-align: center;

        align-items:
          center !important;
      }

      .hero-description {

        margin-left: auto;

        margin-right: auto;
      }

      .benefit-box {

        margin-left: auto;

        margin-right: auto;
      }

      .login-card {

        margin:
          20px auto 0;
      }
    }


    @media (max-width: 575.98px) {

      body {

        background:
          linear-gradient(rgba(4, 14, 27, .82),
            rgba(4, 14, 27, .82)),
          url("../assets/img/ims-safety-bg.png") center / cover no-repeat fixed;
      }

      body.light-mode {

        background:
          linear-gradient(rgba(235, 242, 250, .78),
            rgba(245, 248, 252, .68)),
          url("../assets/img/ims-safety-bg.png") center / cover no-repeat fixed;
      }

      .login-wrapper {

        padding:
          20px 15px;
      }

      .brand-logo {

        font-size: 28px;
      }

      .hero-title {

        font-size: 43px;

        letter-spacing: -2px;
      }

      .hero-description {

        font-size: 15px;
      }

      .benefit-box {

        display:
          none !important;
      }

      .login-card {

        padding:
          32px 22px;

        border-radius:
          24px;
      }

      .login-title {

        font-size: 27px;
      }

      .theme-button {

        top: 15px;

        right: 15px;
      }
    }
  </style>

</head>


<body class="light-mode">


  <!-- =====================================================
         SUCCESS POPUP
    ====================================================== -->

  <div
    class="login-success-overlay"
    id="loginSuccessOverlay">

    <div class="login-success-popup">

      <div class="success-icon">

        <i class="bi bi-check-lg"></i>

      </div>

      <h4>
        Login Successful
      </h4>

      <p id="successMessage">
        Welcome back!
      </p>

      <div class="success-loader">

        <div
          class="spinner-border spinner-border-sm">
        </div>

        <span>
          Redirecting...
        </span>

      </div>

    </div>

  </div>


  <!-- =====================================================
         OVERLAY
    ====================================================== -->

  <div class="page-overlay"></div>


  <!-- =====================================================
         MAIN
    ====================================================== -->

  <div
    class="
            container-fluid
            login-wrapper
        ">


    <!-- =================================================
             BRAND
        ================================================== -->

    <div
      class="
                position-absolute
                top-0
                start-0
                p-4
                p-md-5
            ">

      <div
        class="
                    d-flex
                    align-items-center
                    gap-2
                ">

        <div class="brand-logo">

          <i
            class="bi bi-shield-fill-check">
          </i>

          IMS

        </div>

        <div class="brand-subtitle">

          Safety Management System

        </div>

      </div>

    </div>


    <!-- =================================================
             THEME BUTTON
        ================================================== -->

    <button
      type="button"
      class="theme-button"
      id="themeButton"
      title="Dark mode">

      <i
        class="bi bi-moon-stars-fill">
      </i>

    </button>


    <!-- =================================================
             ROW
        ================================================== -->

    <div
      class="
                row
                min-vh-100
                align-items-center
            ">


      <!-- =================================================
                 LEFT
            ================================================== -->

      <div
        class="
                    col-lg-7
                    d-none
                    d-lg-flex
                ">

        <div
          class="
                        left-content
                        d-flex
                        flex-column
                        align-items-start
                        ps-xl-5
                    ">

          <div
            class="
                            safety-label
                            mb-4
                        ">

            SAFER WORKPLACE
            <br>
            BETTER TOMORROW

          </div>


          <h1
            class="
                            hero-title
                            mb-4
                        ">

            Safety Today,

            <br>

            <span>

              A Safer

              <br>

              Tomorrow

            </span>

          </h1>


          <p
            class="
                            hero-description
                            mb-4
                        ">

            Integrated systems for a safer,
            more compliant, and more
            productive workplace.

          </p>


          <!-- BENEFITS -->

          <div class="benefit-box p-3">

            <div class=" row g-0">

              <div class=" col-4 px-3 ">

                <div class=" benefit-icon text-info mb-2 ">

                  <i
                    class="bi bi-shield-check">
                  </i>

                </div>

                <div
                  class="benefit-title">

                  Safety
                  <br>
                  Compliance

                </div>

              </div>


              <div class=" col-4 px-3 border-start border-end border-secondary ">

                <div class=" benefit-icon text-success mb-2 ">

                  <i
                    class="bi bi-bar-chart-line">
                  </i>

                </div>

                <div
                  class="benefit-title">

                  Efficient
                  <br>
                  Management

                </div>

              </div>


              <div
                class=" col-4 px-3 ">

                <div
                  class=" benefit-icon text-warning mb-2 ">

                  <i
                    class="bi bi-people">
                  </i>

                </div>

                <div
                  class="benefit-title">

                  A Safer
                  <br>
                  Team

                </div>

              </div>

            </div>

          </div>

        </div>

      </div>


      <!-- =================================================
                 LOGIN
            ================================================== -->

      <div
        class=" col-lg-5 d-flex justify-content-center align-items-center  ">


        <div class="login-card">


          <!-- LOGIN HEADER -->

          <div class="text-center">

            <div class="login-logo">

              <i
                class="bi bi-shield-fill-check">
              </i>

            </div>


            <div class="login-title">
              IMS
            </div>


            <div class="login-subtitle">
              Safety Management System
            </div>


            <div class="login-line"></div>


            <div
              class=" small  text-uppercase fw-semibold text-white-50 "
              style=" letter-spacing:4px; ">

              Login to continue

            </div>

          </div>


          <!-- =================================================
                         FORM
                    ================================================== -->

          <form
            action="../backend/controller/AuthController.php"
            method="POST"
            id="loginForm"
            class="mt-4">


            <!-- USERNAME -->

            <div
              class=" input-group-custom  mb-3  ">

              <i
                class=" bi bi-person input-icon  ">
              </i>


              <input
                type="text"
                name="username"
                class=" form-control login-input "
                placeholder="Username or Employee Code"
                autocomplete="username"
                required>

            </div>


            <!-- PASSWORD -->

            <div
              class=" input-group-custom mb-1 ">

              <i
                class=" bi bi-lock input-icon ">
              </i>


              <input
                type="password"
                name="password"
                id="password"
                class=" form-control login-input "
                placeholder="Password"
                autocomplete="current-password"
                required>


              <button
                type="button"
                class="password-toggle"
                id="passwordToggle"
                aria-label="Show password">

                <i
                  class=" bi bi-eye "
                  id="passwordIcon">
                </i>

              </button>

            </div>


            <!-- ERROR -->

            <div
              id="loginError"
              class="login-error">

            </div>


            <!-- BUTTON -->

            <button
              type="submit"
              class=" btn btn-signin w-100 "
              id="loginButton">

              <span
                id="loginButtonText">

                Sign In

              </span>


              <i
                class=" bi bi-arrow-right ms-2 "
                id="loginButtonIcon">
              </i>

            </button>

          </form>


          <!-- FOOTER -->

          <div
            class=" text-center mt-4  ">

            <div
              class="login-footer">

              IMS Safety Management System v1.0

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>


  <!-- =====================================================
         BOOTSTRAP JS
    ====================================================== -->

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
  </script>


  <script>
    /* =====================================================
           PASSWORD SHOW / HIDE
        ====================================================== */

    const password =
      document.getElementById("password");

    const passwordToggle =
      document.getElementById("passwordToggle");

    const passwordIcon =
      document.getElementById("passwordIcon");


    passwordToggle.addEventListener(
      "click",
      function() {

        if (
          password.type === "password"
        ) {

          password.type = "text";

          passwordIcon.classList.remove(
            "bi-eye"
          );

          passwordIcon.classList.add(
            "bi-eye-slash"
          );

          passwordToggle.setAttribute(
            "aria-label",
            "Hide password"
          );

        } else {

          password.type = "password";

          passwordIcon.classList.remove(
            "bi-eye-slash"
          );

          passwordIcon.classList.add(
            "bi-eye"
          );

          passwordToggle.setAttribute(
            "aria-label",
            "Show password"
          );
        }

      }
    );


    /* =====================================================
       DARK / LIGHT MODE
    ====================================================== */

    const themeButton =
      document.getElementById(
        "themeButton"
      );

    const themeIcon =
      themeButton.querySelector("i");


    themeButton.addEventListener(
      "click",
      function() {

        document.body.classList.toggle(
          "light-mode"
        );


        const isLight =
          document.body.classList.contains(
            "light-mode"
          );


        if (isLight) {

          themeIcon.classList.remove(
            "bi-sun-fill"
          );

          themeIcon.classList.add(
            "bi-moon-stars-fill"
          );

          themeButton.title =
            "Dark mode";

        } else {

          themeIcon.classList.remove(
            "bi-moon-stars-fill"
          );

          themeIcon.classList.add(
            "bi-sun-fill"
          );

          themeButton.title =
            "Light mode";
        }

      }
    );


    /* =====================================================
       LOGIN
    ====================================================== */

    const loginForm =
      document.getElementById(
        "loginForm"
      );

    const loginButton =
      document.getElementById(
        "loginButton"
      );

    const loginButtonText =
      document.getElementById(
        "loginButtonText"
      );

    const loginButtonIcon =
      document.getElementById(
        "loginButtonIcon"
      );

    const loginError =
      document.getElementById(
        "loginError"
      );

    const successOverlay =
      document.getElementById(
        "loginSuccessOverlay"
      );


    loginForm.addEventListener(
      "submit",
      async function(event) {

        event.preventDefault();


        /* CLEAR ERROR */

        loginError.textContent = "";

        loginError.classList.remove(
          "show"
        );


        /* LOADING */

        loginButton.disabled = true;

        loginButtonText.textContent =
          "Logging in...";

        loginButtonIcon.className =
          "spinner-border spinner-border-sm ms-2";


        try {

          const formData =
            new FormData(
              loginForm
            );


          /* =========================================
             SEND REQUEST
          ========================================== */

          const response =
            await fetch(
              loginForm.action, {
                method: "POST",

                body: formData,

                credentials: "same-origin",

                headers: {
                  "X-Requested-With": "XMLHttpRequest",

                  "Accept": "application/json"
                }
              }
            );


          /* =========================================
             READ RESPONSE AS TEXT FIRST
          ========================================== */

          const responseText =
            await response.text();


          console.log(
            "AuthController response:",
            responseText
          );


          /* =========================================
             CHECK HTTP STATUS
          ========================================== */

          if (!response.ok) {

            throw new Error(
              "Server error (" +
              response.status +
              ")"
            );
          }


          /* =========================================
             PARSE JSON
          ========================================== */

          let result;

          try {

            result =
              JSON.parse(
                responseText
              );

          } catch (jsonError) {

            console.error(
              "Invalid JSON from server:",
              responseText
            );


            throw new Error(
              "The server returned an invalid response."
            );
          }


          /* =========================================
             SUCCESS
          ========================================== */

          if (
            result.success === true
          ) {

            successOverlay.classList.add(
              "show"
            );


            document.body.style.overflow =
              "hidden";


            setTimeout(
              function() {

                /*
                 * replace() instead of href
                 * prevents returning to login
                 * through browser history.
                 */

                window.location.replace(
                  result.redirect ||
                  "../dashboard.php"
                );

              },
              1500
            );


            return;
          }


          /* =========================================
             LOGIN FAILED
          ========================================== */

          loginButton.disabled =
            false;

          loginButtonText.textContent =
            "Sign In";

          loginButtonIcon.className =
            "bi bi-arrow-right ms-2";


          loginError.textContent =
            result.message ||
            "Invalid username or password.";


          loginError.classList.add(
            "show"
          );


          password.focus();

        }


        /* =========================================
           ERROR
        ========================================== */
        catch (error) {

          console.error(
            "LOGIN ERROR:",
            error
          );


          loginButton.disabled =
            false;

          loginButtonText.textContent =
            "Sign In";

          loginButtonIcon.className =
            "bi bi-arrow-right ms-2";


          /*
           * Show the actual error
           * instead of falsely saying
           * server is disconnected.
           */

          if (
            error.message.includes(
              "invalid response"
            )
          ) {

            loginError.textContent =
              "Server error. Check AuthController.php.";

          } else {

            loginError.textContent =
              error.message ||
              "Unable to connect to the server. Please try again.";
          }


          loginError.classList.add(
            "show"
          );

        }

      }
    );


    /* =====================================================
       BACK BUTTON / BFCACHE PROTECTION
    ====================================================== */

    window.addEventListener(
      "pageshow",
      function(event) {

        if (
          event.persisted
        ) {

          window.location.reload();

        }

      }
    );
  </script>

</body>

</html>