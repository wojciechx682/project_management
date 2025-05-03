<div class="users users-content">
    <div class="user-id">%s</div>
    <div class="user-first-name">%s</div>
    <div class="user-last-name">%s</div>
    <div class="user-email">%s</div>
    <div class="user-role">%s</div>
    <div class="user-created-at">%s</div>
    <div class="user-is-approved">%s</div>
    <div class="user-action">
        <div class="user-action-button">
            Manage <i class="icon-down-open"></i>
        </div>
        <div class="user-options-container">
            <div class="user-action-options hidden">
                <div class="user-option" onclick="showConfirmationModal(%s, 'accept')">Accept</div>
                <div class="user-option" onclick="showConfirmationModal(%s, 'reject')">Reject</div>
            </div>
        </div>
    </div>
</div>