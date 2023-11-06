<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage='tweets'></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Dashboard"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="card">
            <a href="{{ route('search-terms.create') }}" class="btn btn-icon btn-2 btn-primary col-md-1">
                <span class="btn-inner--icon"><i class="fas fa-plus"></i> </span>
            </a>
            <div class="table-responsive">
                <table class="table align-items-center mb-0">
                    <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keywords</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($searchTerms as $searchTerm)
                        <form method="POST" id="delete-search-term" action="{{ route('search-terms.destroy', ['search_term' => $searchTerm->id]) }}">
                            @csrf
                            @method('DELETE')
                        </form>
                        <form method="POST" id="search-with-keyword" action="{{ route('search-terms.search', ['search_term' => $searchTerm->id]) }}">
                            @csrf
                        </form>
                        <tr>
                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-xs">{{ $searchTerm->tags->pluck('name')->join(', ') }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td class="align-right">
                                <a href="{{ route('search-terms.edit', ['search_term' => $searchTerm->id]) }}" class="text-secondary font-weight-normal text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                    <i class="fas fa-edit fs-4"></i>
                                </a>
                                <a href="#" onclick="document.getElementById('delete-search-term').submit();" class="text-secondary font-weight-normal text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                    <i class="fas fa-trash fs-4"></i>
                                </a>
                                <a href="#" onclick="document.getElementById('search-with-keyword').submit();" class="text-secondary font-weight-normal text-xs" data-toggle="tooltip" data-original-title="Edit user">
                                    <i class="fas fa-search fs-4"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {!! $searchTerms->links() !!}
            </div>
        </div>
    </main>
</x-layout>
