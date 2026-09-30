<!DOCTYPE HTML>
<HTML>
    <head>
    <title>👍aN Investments</title>
    <link rel="icon" type="image/png" href="/images/logo004.png">
    
    <style>
        body{
            font-family: "Times New Roman";
            font-weight:bold;
            margin: 0 auto;
            text-align:center;
        }

        .logo{
            border-radius: 45px;
            margin-top:20px;
        }

        .login-box {
            width: 250px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        input {
            width: 94%;
            padding: 8px;
            margin-top: 10px;
        }

        button {
            margin-top: 15px;
            padding: 8px 20px;
            cursor: pointer;
        }

        #error {
            color: red;
            font-style: italic;
        }
    </style>
</head>
    <BODY>
    <img src="/images/logo04.png" class="logo">
        <div class="login-box">
                <h2>Login Form</h2>
                
                <form id="loginform" method="POST" action="/login">

                    @csrf
                    <input type="email" id="email" name="email" placeholder="Email" required>
                    <input type="password" id="password" name="password" placeholder="Password" required> <!-- minlength="10" -->

                    <button type="submit">Login</button>
                    <!-- <button onclick="alert('Hello')">Click</button> -->
                    
                </form>

                @if(session('error'))
                    <script>
                        alert("{{ session('error') }}")
                    </script>
                    <p style="color:red">{{ session('error') }}</p>
                @endif

                @if(session('success'))
                    <script>
                        alert("{{ session('success') }}")
                    </script>
                    <p style="color:green">{{ session('success') }}</p>
                @endif

                    <a href="/signup" style="top: 9px;position: relative;">Create new account</a>

        </div>
        <p id="error"></p>
        <p id="success"></p> 

        <script>
            document.getElementById("loginform").addEventListener ("submit", function (e) {
                
                let email = document.getElementById("email").value.trim();
                let password = document.getElementById("password").value.trim();

                let error = "";

                // if(email === ""|| password === ""){
                //     error = "All field Required";
                //     e.preventDefault();              this is not need because of required tag in input form
                // }

                
                // document.getElementById("error").innertext= error;
            });
        </script>
    </BODY>
</HTML>