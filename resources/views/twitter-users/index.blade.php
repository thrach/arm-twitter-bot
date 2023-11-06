<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage='tweets'></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Dashboard"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="card">
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Username</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Handler</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                        <th class="text-secondary opacity-7"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($twitterUsers as $user)
                        <tr>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-xs">{{ $user->name }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="text-xs font-weight-bold mb-0">{{ $user->username }}</p>
                            </td>
                            <td class="align-middle text-sm">
                                <span class="badge badge-sm bg-gradient-{{$user->status}}">{{$user->status_text}}</span>
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('twitter-users.show', ['twitter_user' => $user->id]) }}" class="text-secondary font-weight-normal text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $twitterUsers->links() !!}
            </div>
        </div>
    </main>
</x-layout>
