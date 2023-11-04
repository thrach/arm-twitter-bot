@extends('layouts.app')

@section('content')
    <form action="{{ route('search-terms.update', ['search_term' => $searchTerm->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="keywords" class="form-label">Keywords</label>
            <select name="tags[]" id="keywords" class="form-control select2" multiple="multiple">
                @foreach($searchTerm->tags as $tag)
                    <option value="{{ $tag->name }}" selected>{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="exclusions" class="form-label">Keyword exclusions</label>
            <select name="exclusions[]" id="exclusions" class="form-control select2" multiple="multiple">
                @if($searchTerm->keyword->searchTermExclusion->tags)
                    @foreach($searchTerm->keyword->searchTermExclusion->tags as $tag)
                        <option value="{{ $tag->name }}" selected>{{ $tag->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="mb-3" id="replies_content">
            <label for="replies" class="form-label">Reply</label>
            @foreach($searchTerm->keyword->replies as $reply)
                <textarea name="replies[{{$reply->id}}]" data-id="{{ $reply->id }}" id="replies_{{$reply->id}}" class="form-control mt-2 replies">{{ $reply->reply }}</textarea>
            @endforeach
        </div>
        <button type="button" class="btn btn-primary" id="add-reply">Add reply</button>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
@endsection

@push('footer-scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                tags: true,
                tokenSeparators: [',', ' ']
            });

            // Function to add or remove the remove buttons as needed
            function manageRemoveButtons() {
                if ($('.replies').length <= 1) {
                    // If there's only one reply left, remove all remove buttons
                    $('.remove-reply').remove();
                } else {
                    // If there's more than one reply, add remove buttons next to each reply
                    $('.remove-reply').remove(); // First, clear all to avoid duplication
                    $('.replies').each(function() {
                        var $this = $(this);
                        var removeButton = $('<button type="button" class="btn btn-danger remove-reply mt-1">Remove</button>');

                        removeButton.click(function() {
                            var replyId = $this.data('id');
                            if (replyId !== 'new') {
                                // Send AJAX request to server to delete the reply
                                $.ajax({
                                    url: '/delete-reply/' + replyId,
                                    type: 'POST',
                                    data: {
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(result) {
                                        // Success logic here
                                    },
                                    error: function(request, msg, error) {
                                        // Error logic here
                                    }
                                });
                            }
                            // Remove the textarea and the button
                            $this.remove();
                            $(this).remove();
                            manageRemoveButtons(); // Re-evaluate the remove buttons
                        });

                        $this.after(removeButton);
                    });
                }
            }

            $('#add-reply').click(function() {
                var replies = $('.replies');
                var lastReply = replies.last();
                var newReply = lastReply.clone().val('').data('id', 'new');
                newReply.attr('name', 'replies[]')
                $('#replies_content').append(newReply);
                manageRemoveButtons(); // Re-evaluate the remove buttons after adding new reply
            });

            // Initialize the remove buttons based on initial state
            manageRemoveButtons();
        });
    </script>
@endpush
