<html>
  <head>
    <link rel="icon" type="image/png" href="vulnbank/assets/images/favicon.ico">
    <link rel="stylesheet" href="vulnbank/assets/css/bootstrap.min.css">
    <style>
      .card {
        box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
      }
    
      .card {
        margin-top: 10px;
        box-sizing: border-box;
        border-radius: 2px;
        background-clip: padding-box;
      }
      .card span.card-title {
        color: #2e6da4;
        font-size: 24px;
        font-weight: 300;
        text-transform: uppercase;
      }
    
      .card .card-image {
        position: relative;
        overflow: hidden;
      }
      .card .card-image img {
        border-radius: 2px 2px 0 0;
        background-clip: padding-box;
        position: relative;
        z-index: -1;
      }
      .card .card-image span.card-title {
        position: absolute;
        bottom: 0;
        left: 0;
        padding: 16px;
      }
      .card .card-content {
        padding: 16px;
        border-radius: 0 0 2px 2px;
        background-clip: padding-box;
        box-sizing: border-box;
      }
      .card .card-content p {
        margin: 0;
        color: inherit;
      }
      .card .card-content span.card-title {
        line-height: 48px;
      }
      .card .card-action {
        border-top: 1px solid rgba(160, 160, 160, 0.2);
        padding: 16px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-image">
              <img class="img-responsive" src="images/bl.jpg">
              <span class="card-title">Business Logic Attack</span>
            </div>
            <div class="card-content">
              <p>Most security problems are weaknesses in an application that result from a broken or missing security control (authentication, access control, input validation, etc...). By contrast, business logic vulnerabilities are ways of using the legitimate processing flow of an application in a way that results in a negative consequence to the organization</p>
            </div>
            <div class="card-action">
              <a href="vulnbank/online/portal.php?tour=businesslogic" class="btn btn-primary btn-block">Start Tour</a>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-image">
              <img class="img-responsive" src="images/xss.jpg">
              <span class="card-title">Cross-Site Scripting Atttack (XSS)</span>
            </div>
            <div class="card-content">
              <p>Cross-Site Scripting (XSS) attacks are a type of injection, in which malicious scripts are injected into otherwise benign and trusted web sites. XSS attacks occur when an attacker uses a web application to send malicious code, generally in the form of a browser side script, to a different end user. Flaws that allow these attacks to succeed are quite widespread and occur anywhere a web application uses input from a user within the output it generates without validating or encoding it.</p>
            </div>
            <div class="card-action">
              <a href="vulnbank/online/portal.php?tour=xss" class="btn btn-primary btn-block">Start Tour</a>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-image">
              <img class="img-responsive" src="images/sqli.jpg">
              <span class="card-title">SQL Injection</span>
            </div>
            <div class="card-content">
              <p>SQL injection is a code injection technique, used to attack data-driven applications, in which nefarious SQL statements are inserted into an entry field for execution (e.g. to dump the database contents to the attacker).</p>
            </div>
            <div class="card-action">
              <a href="vulnbank/online/portal.php?tour=sqli" class="btn btn-primary btn-block">Start Tour</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
