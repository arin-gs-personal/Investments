<!DOCTYPE HTML>
<HTML>
    <head>
    <title>aN Sign Up</title>
    <link rel="icon" type="image/png" href="/images/logo004.png">
    
    <style>
        body{
            font-family: Arial;
            /* margin:center; */
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
            <h2>Sign Up Form</h2>
                <form method="POST" action="/signup">
                    @csrf
                    <input type="text" name="name" placeholder="Name"><br><br>
                    <input type="email" name="email" placeholder="Email"><br><br>
                    <input type="builder" name="builder" placeholder="Builder"><br><br>
                    <input type="password" name="password" minlength="10" placeholder="Password"><br><br>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password"><br><br>

                    <button type="submit">Signup</button>
                </form>


                <br><p> Already have an account? <a href="{{ url('/login') }}" class="login-btn">Login</a> </p>


                @if(session('error'))
                    <p style="color:red">{{ session('error') }}</p>
                @endif

                <!-- @if(session('success'))
                    <p style="color:green">{{ session('success') }}</p>
                @endif -->

        </div>


        <!-- handles all the error -->
        @if ($errors->any())
            <div style="background:#ffe6e6; padding:10px; border:1px solid red; margin-bottom:15px;">
                <ul style="color:red; margin:0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <script>
            document.getElementById("loginform").addEventListener ("submit", function (e) {
                e.preventDefault();
                
                let email = document.getElementById("email");
                let password = document.getElementById("password").value.trim();

                let error = "";

                // if (error === ""){
                //     alert("Got Success!");
                // }
            });
        </script>
    </BODY>
</HTML>