<div id="add-comment" class="hidden">

    <h2>Add Comment</h2>

    <i class="icon-cancel" onclick="closeAddCommentWindow()"></i>

    <hr>

    <form id="add-comment-form" method="post" action="addComment.php">

        <!-- Ukryte pole z ID zadania -->
        <input type="hidden" id="comment-task-id" name="task_id" value="%s">

        <div class="task-details">
            <label for="comment-content" class="task-details-desc-left project-details-desc-left">
                Comment
            </label>
            <textarea
                    id="comment-content"
                    name="content"
                    class="task-details-desc project-details-desc"
                    placeholder="Write your comment here..."
                    maxlength="255"
                    minlength="10"
                    required
                    onfocus="resetError(this)">
            </textarea>
        </div>

        <span class="task-comment-error hidden">
            Comment should contain between 10 and 255 characters and cannot contain special characters.
        </span>

        <div class="task-details task-details-button">
            <button type="submit" class="btn-link btn-link-static btn-submit-project">Submit</button>
            <button type="button" class="btn-link btn-link-static btn-cancel" onclick="closeAddCommentWindow()">Cancel</button>
        </div>

    </form>

</div>
