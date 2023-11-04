@extends('layouts.app')

@section('content')
    <form action="{{ route('search-terms.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="keywords" class="form-label">Keywords</label>
            <select name="tags[]" id="keywords" class="form-control select2" multiple="multiple">
            </select>
        </div>
        <div class="mb-3">
            <label for="exclusions" class="form-label">Keyword exclusions</label>
            <select name="exclusions[]" id="exclusions" class="form-control select2" multiple="multiple">
            </select>
        </div>
        <div class="mb-3" id="replies_content">
            <label for="replies" class="form-label">Reply</label>
            <textarea name="replies[]" data-id="1" id="replies_1" class="form-control mt-2 replies"></textarea>
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

            // Function to manage the remove buttons
            function manageRemoveButtons() {
                if ($('.replies').length === 1) {
                    $('.remove-reply').remove(); // Remove all remove buttons if only one reply is present
                } else {
                    // Add a remove button to each reply if not already present
                    $('.replies').each(function() {
                        let reply = $(this);
                        if (!reply.next().hasClass('remove-reply')) {
                            let removeButton = $('<button type="button" class="btn btn-danger remove-reply mt-1">Remove</button>');
                            removeButton.click(function() {
                                $(this).prev('.replies').remove();
                                $(this).remove();
                                manageRemoveButtons(); // Update remove buttons after removing
                            });
                            reply.after(removeButton);
                        }
                    });
                }
            }

            // Initial manage remove buttons call
            manageRemoveButtons();

            $('#add-reply').click(function() {
                let replies = $('.replies');
                let lastReply = replies.last();
                let lastReplyId = parseInt(lastReply.data('id')) || 0; // In case no replies exist yet
                let newReplyId = lastReplyId + 1;
                let newReply = lastReply.clone();

                newReply.data('id', newReplyId);
                newReply.val('');
                newReply.attr('id', 'replies_' + newReplyId);
                newReply.attr('name', 'replies[]');
                newReply.addClass('mt-2');

                // Append the new reply
                $('#replies_content').append(newReply);

                // Manage remove buttons after adding a new one
                manageRemoveButtons();
            });
        });
    </script>
@endpush
