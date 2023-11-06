<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage="profile"></x-navbars.sidebar>
    <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage='Profile'></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid px-2 px-md-4">
            <div class="page-header min-height-300 border-radius-xl mt-4"
                 style="background-image: url('{{ asset('assets/img/symfony-of-the-stones.jpg') }}');">
                <span class="mask  bg-gradient-primary  opacity-6"></span>
            </div>
            <div class="card card-body mx-3 mx-md-4 mt-n6">
                <div class="row gx-4 mb-2">
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                {{ $twitterUser->name }}
                            </h5>
                            <p class="mb-0 font-weight-normal text-sm">
                                {{ $twitterUser->username }}
                            </p>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="row">
                        <div class="col-12 col-xl-4">
                            <div class="card card-plain h-100">
                                <div class="card-header pb-0 p-3">
                                    <h6 class="mb-0">Platform Settings</h6>
                                </div>
                                <div class="card-body p-3">
                                    <form action="{{ route('twitter-users.update', ['twitter_user' => $twitterUser->id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <h6 class="text-uppercase text-body text-xs font-weight-bolder">Account</h6>
                                        <ul class="list-group">
                                            <li class="list-group-item border-0 px-0">
                                                <div class="form-check form-switch ps-0">
                                                    <input name="can_reply_with_this_account" class="form-check-input ms-auto" type="checkbox"
                                                           id="can_reply_with_this_account" @checked($twitterUser->can_reply_with_this_account)>
                                                    <label class="form-check-label text-body ms-3 text-truncate w-80 mb-0"
                                                           for="can_reply_with_this_account">Can Reply With This Account</label>
                                                </div>
                                            </li>
                                        </ul>

                                        <button type="submit" class="btn btn-primary">Update Settings</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layout>
