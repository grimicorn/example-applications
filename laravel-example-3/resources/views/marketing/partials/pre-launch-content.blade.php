<div class="register-bar">
    <div class="container">
        <div class="row">
            <div class="register-bar-first text-center col-sm-10 col-sm-offset-1">
                <p class="fc-color4 cta">Firm Exchange is revolutionizing the way people buy and sell small businesses.</p>
                @guest
                    <p class="">Our focus on serious buyers and sellers means more deals get done, more efficiently than ever.</p>
                    <a href="{{ route('register') }}" class="btn btn-color5 text-center">Get Started</a>
                @endguest
            </div> {{-- /.class --}}

        </div> {{-- /.row --}}
    </div> {{-- /.container --}}
</div> {{-- /.register-bar --}}

<!-- <div class="container text-center content-section">
<pre>
    Coming in early 2018, Firm Exchange will revolutionize the way people buy and sell small businesses.  Our focus on serious buyers and sellers means more deals get done, more efficiently than ever.

    Sign up now with just your e-mail address to secure your spot on the platform and earn one month’s subscription completely free.

    <a href="{{ route('register') }}" class="btn">Get Started</a>
</pre>
</div> -->
