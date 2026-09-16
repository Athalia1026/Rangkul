<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Login</title>

  <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
    rel="stylesheet"
  >

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      min-height: 100vh;
      font-family: "Plus Jakarta Sans", sans-serif;
      background: #F5F7F4;
      color: #171717;
    }

    .login-page {
      min-height: 100vh;
      display: flex;
    }

    
    .left-side {
      width: 45%;
      min-height: 100vh;
      position: relative;

      background:
        linear-gradient(
          rgba(255, 255, 255, 0.5),
          rgba(255, 255, 255, 0.5)
        ),
        url("/images/background-login.jpg");

      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .logo {
      position: absolute;
      top: 32px;
      left: 40px;
    }

    .logo img {
      width: 150px;
      height: auto;
    }

    .brand-content {
      position: absolute;
      left: 40px;
      bottom: 48px;
      max-width: 420px;
      color: white;
    }

    .brand-content h2 {
      font-size: 30px;
      line-height: 1.3;
      font-weight: 600;
      margin-bottom: 12px;
    }

    .brand-content p {
      font-size: 14px;
      line-height: 1.7;
      color: rgba(255, 255, 255, 0.85);
    }


    .right-side {
      width: 55%;
      min-height: 100vh;

      display: flex;
      align-items: center;
      justify-content: center;

      padding: 60px;
      background: #F5F7F4;
    }

    .login-container {
      width: 100%;
      max-width: 600px;
    }

    .welcome-label {
      margin-bottom: 10px;

      font-size: 25px;
      font-weight: 600;
      letter-spacing: 0.1em;
      text-transform: uppercase;

      color: #066939;
    }

    .login-container h1 {
      font-size: 35px;
      line-height: 1.2;
      font-weight: 600;
      letter-spacing: -0.03em;
      text-align: center;
    }

    .description {
      margin-top: 16px;
      margin-bottom: 32px;

      font-size: 15px;
      line-height: 1.7;
      color: #6b7280;
      text-align: center;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      display: block;
      margin-bottom: 8px;

      font-size: 15px;
      font-weight: 600;
      color: #272727;
    }

    .form-input {
      width: 100%;
      height: 50px;

      padding: 0 16px;

      border: 1px solid #d1d5db;
      border-radius: 8px;

      background: white;

      font-family: Plus Jakarta Sans;
      font-size: 15px;

      outline: none;
    }

    .form-input:focus {
      border-color: #066939;
      box-shadow: 0 0 0 3px rgba(6, 105, 57, 0.08);
    }

    .password-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .password-header .form-label {
      margin-bottom: 8px;
    }

    .forgot-password {
      font-size: 10px;
      color: #6b7280;
      text-decoration: none;
    }

    .forgot-password:hover {
      color: #066939;
    }

    .login-button {
        width: 50%;
        height: 50px;

        margin: 4px auto 0;
        display: block;

      border: none;
      border-radius: 8px;

      background: #066939;
      color: white;

      font-family: Plus Jakarta Sans;
      font-size: 15px;
      font-weight: 600;

      cursor: pointer;
    }

    .login-button:hover {
      background: #05582f;
    }

    .register {
      margin-top: 28px;
      padding-top: 24px;

      border-top: 1px solid #dfe4df;

      text-align: center;

      font-size: 13px;
      color: #737373;
    }

    .register a {
      color: #066939;
      font-weight: 600;
      text-decoration: none;
    }

    .register a:hover {
      text-decoration: underline;
    }

    @media (max-width: 900px) {
      .left-side {
        display: none;
      }

      .right-side {
        width: 100%;
        padding: 40px 24px;
      }

      .login-container h1 {
        font-size: 32px;
      }
    }

    @media (max-width: 480px) {
      .right-side {
        padding: 32px 20px;
      }

      .login-container h1 {
        font-size: 28px;
      }
    }
  </style>
</head>

<body>

  <main class="login-page">

    <!-- PANEL KIRI -->
    <section class="left-side">

      <div class="logo">
        <img
          src="/images/logo.png"
          alt="Rangkul"
        >
      </div>

      <div class="brand-content">

        <h2>

        </h2>

        <p>

        </p>

      </div>

    </section>


   
    <section class="right-side">

      <div class="login-container">

        <div class="welcome-label">
        </div>

        <h1>
          Selamat Datang Kembali!
        </h1>

        <p class="description">
          Masuk ke akun Anda dan terus berikan dukungan terbaik bagi panti asuhan yang membutuhkan.
        </p>


        <form>

          <div class="form-group">

            <label
              for="email"
              class="form-label"
            >
              Email
            </label>

            <input
              type="email"
              id="email"
              name="email"
              class="form-input"
              required
            >

          </div>


          <div class="form-group">

            <div class="password-header">

              <label
                for="password"
                class="form-label"
              >
                Password
              </label>

              <a
                href="#"
                class="forgot-password"
              >
                Lupa password?
              </a>

            </div>

            <input
              type="password"
              id="password"
              name="password"
              class="form-input"
              required
            >

          </div>


          <button
            type="submit"
            class="login-button"
          >
            Masuk
          </button>

        </form>


        <div class="register">
          Belum memiliki akun?
          <a href="#">
            Daftar sekarang
          </a>
        </div>

      </div>

    </section>

  </main>

</body>
</html>