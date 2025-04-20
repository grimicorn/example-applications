@extends('layouts.marketing')

@section('content')

<div class="register-bar">
    <div class="container">
        <div class="row">
            <div class="register-bar-first text-center col-sm-10 col-sm-offset-1">
                <h2 class="fc-color4 cta">Firm Exchange is the modern approach to buying a small business, with features built to help make your deal a success.</h2>
                  <p class="">Looking to buy or sell a small business? Do it confidently with Firm Exchange.</p>
                @guest
                  <a href="{{ route('register') }}" class="btn btn-color5 text-center">Get Started</a>
                @endguest
            </div> {{-- /.class --}}

        </div> {{-- /.row --}}
    </div> {{-- /.container --}}
</div> {{-- /.register-bar --}}

<div class="feature-half">
    <div class="container">
        <div class="row">
            <div class="col-md-8 content-left">
                <h2 class="h3 mt0">Customizable User Dashboard</h2>
              <p><em class="text-bold">Easy access to everything you need, right at your fingertips.</em> No ads, upsells, or other distractions.</p>
              <ul>
	              <li>Business Inquiries help keep track of every inbound lead. Start a conversation, and when you’re ready, launch an Exchange Space to manage the deal.</li>
	              <li>Follow business listings that interest you with your personalized Favorites list.</li>
	              <li>Protect your account and information with an enhanced level of security by enabling Two-Factor Authorization.</li>
	              <li>Save searches in your Watch List and be automatically alerted when new listings are added that match your criteria.</li>
							</ul>
            </div> {{-- /.class --}}
            <div class="col-md-4">
              <div style="background: url('/img/fe-dashboard.png'); width: 100%; min-height: 400px; background-size: cover; background-position: center;" title="Customizable dashboard let’s users focus on what is most important to them"></div>
            </div> {{-- /.class --}}
        </div> {{-- /.row --}}
    </div> {{-- /.container --}}
</div> {{-- /.feature-half --}}

<div class="feature-half">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-push-4 content-right">
                <h2 class="h3 mt0">Listing Completion Rating</h2>
              <p><em class="text-bold">Better listing placement at no extra cost.</em> Other sites ask sellers to pay more money to get higher placement in results. Our unique rating system, the Listing Completion Rating (LC Rating), emphasizes information completeness and rewards more thorough listings with higher placement in search results. The reason is simple—getting relevant information in front of buyers early in the process helps get deals done more quickly.</p>
              <p>Driven by descriptive business information and historical financials, the LC Rating for each listing is easily monitored so sellers can keep track of where they stand. To learn more about how the Listing Completion Rating works, visit our <a href="/lc-rating">Listing Completion Rating page</a>.</p>
            </div> {{-- /.class --}}
            <div class="col-md-4 col-md-pull-8">
              <div style="background: url('/img/LCRating_Star_System.jpg'); width: 100%; min-height: 240px; background-size: cover; background-position: center;" title="More transparency in listings are rewarded with higher search results placement"></div>
            </div>
            {{-- /.class --}}
        </div> {{-- /.row --}}
    </div> {{-- /.container --}}
</div> {{-- /.feature-half --}}

<div class="feature-half">
    <div class="container">
        <div class="row">
            <div class="col-md-8 content-left">
                <h2 class="h3 mt0">Historical Financials Tool</h2>
              <p><em class="text-bold">A win-win for buyers and sellers.</em> For sellers, one of the hardest parts of the deal is gathering your financial information for would-be buyers to review. Our easy-to-use Historical Financials Tool streamlines the process while allowing the seller to retain complete control over his or her information. For buyers, the result is a standardized, exportable set of data that provides a head start on their due diligence process.</p>
            </div> {{-- /.class --}}
            <div class="col-md-4">
              <div style="background: url('/img/fe_history_center.jpg'); width: 100%; min-height: 200px; background-size: cover; background-position: center;" title="Firm Exchange helps streamline the sharing of key historical financials"></div>
            </div> {{-- /.class --}}
        </div> {{-- /.row --}}
    </div> {{-- /.container --}}
</div> {{-- /.feature-half --}}

<div class="feature-grid">
    <div class="container">
        <div class="row">
        	<div class="col-sm-10 col-sm-offset-1">
        		<h2>Exchange Space with Integrated Diligence Center</h2>

                <h3 class="subhead fw-bold">Unique deal space brings all parties together and offers a one-stop-shop for the entire due diligence process.</h3>
            <p>Firm Exchange is the hub where successful deals happen. No more losing track of email conversations or shared documents. All parties stay on the same page and can work together to close the deal.</p>
        	</div>
        </div>
        <div class="row">
          <div class="col-sm-4 feature-grid-item">
          	<i class="fa fa-users" aria-hidden="true"></i>
          	<p>Sellers invite interested buyers and advisors into each Exchange Space, providing a platform for all parties involved in the deal to stay on the same page</p>
          </div> {{-- /.class --}}
          <div class="col-sm-4 feature-grid-item">
          	<i class="fa fa-arrow-right" aria-hidden="true"></i>
          	<p>Stage-based tracking helps you monitor progress on every potential deal</p>
          </div> {{-- /.class --}}
          <div class="col-sm-4 feature-grid-item">
          	<i class="fa fa-file-text-o" aria-hidden="true"></i>
          	<p>Sellers control access to standardized, exportable historical financial records</p>
          </div> {{-- /.class --}}
          <div class="col-sm-4 feature-grid-item">
          	<i class="fa fa-bell" aria-hidden="true"></i>
          	<p>Real-time notifications so you never miss an update</p>
          </div> {{-- /.class --}}
          <div class="col-sm-4 feature-grid-item">
          	<i class="fa fa-folder" aria-hidden="true"></i>
          	<p>Organize questions by topic, as broad or as specific as you need</p>
          </div> {{-- /.class --}}
          <div class="col-sm-4 feature-grid-item">
          	<i class="fa fa-comments-o" aria-hidden="true"></i>
          	<p>Seamless record of communication within every diligence topic</p>
          </div> {{-- /.class --}}
          <div class="col-sm-4 feature-grid-item">
          	<i class="fa fa-search" aria-hidden="true"></i>
          	<p>Powerful search tool works across topics and within messages</p>
          </div> {{-- /.class --}}
          <div class="col-sm-4 feature-grid-item">
          	<i class="fa fa-paperclip" aria-hidden="true"></i>
          	<p>Attach documents to any message and view all uploaded documents in a centralized repository</p>
          </div> {{-- /.class --}}
          <div class="col-sm-4 feature-grid-item">
          	<i class="fa fa-check" aria-hidden="true"></i>
          	<p>Marking items as complete keeps the focus on topics that still need to be addressed</p>
          </div> {{-- /.class --}}
        </div> {{-- /.row --}}
    </div> {{-- /.container --}}
</div> {{-- /.feature-grid --}}

{{--feature buckets--}}
    @include('marketing.partials.feature-cards', [
        'cards' => [
            [
                'title' => 'Blog',
                'content' => 'Our blog features a wide-ranging collection of articles, great for novices and veterans alike. Learn more about topics such as: how to position your business for a successful sale, how to value your business and set an asking price, and how potential buyers might approach your business.',
                'iconClass' => 'fa fa-newspaper-o',
            ],

            [
                'title' => 'Professional Directory',
                'content' => 'Find and connect with the missing piece of your deal team. Business brokers, accountants, lawyers, and more can list their profile in the Professional Directory and network with the Firm Exchange community. No ads, no fees, just another way we help get more deals done more efficiently.',
                'iconClass' => 'fa fa-address-card-o'
            ],
         ],
            'title' => 'Community Resources',
            'colClass' => 'col-sm-6'
    ])

@endsection
