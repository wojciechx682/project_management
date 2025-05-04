<!--<div class="task-details">
    <div class="task-details-id">ID: </div>
    <div class="task-details-title">Title: </div>
    <div class="task-details-desc">Description: </div>
    <div class="task-details-priority">Priority: </div>
    <div class="task-details-status">Status: </div>
    <div class="task-details-due-date">Due date: </div>
    <div class="task-details-project-id">Project ID: </div>
    <div class="task-details-assigned-user">Assigned user: </div>
    <div class="task-details-task-created">Created at: </div>
    <div class="task-details-task-updated">Updated at: </div>
</div> <hr>-->

<div class="task task-content">
    <div class="task-id">%s</div>
    <div class="task-title">
        <a href="#" onclick="toggleTaskDetails(%s)">%s</a>
    </div>
    <!--<div class="task-desc"></div>-->
    <div class="task-priority">%s</div>
    <div class="task-status">%s</div>
    <div class="task-due-date">%s</div>
    <!--<div class="project-created-at task-project-id"></div>-->
    <div class="task-assigned-user">%s %s</div>
    <div class="task-created-at">%s</div>
    <div class="task-manage">
        <div class="task-action-button">
            Manage <i class="icon-down-open"></i>
        </div>
        <div class="task-options-container">
            <div class="task-action-options hidden">
                <div class="task-option" onclick="toggleEditTaskWindow(%s)">Edit</div>
                <div class="task-option task-option-delete" onclick="toggleDeleteTaskWindow(%s)">Delete</div>
            </div>
        </div>
    </div>
</div>

