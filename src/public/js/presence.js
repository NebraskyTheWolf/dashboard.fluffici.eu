const success = 'pusher:subscription_succeeded'
const memberJoined = 'pusher:member_added'
const memberLeft = 'pusher:member_removed'

function startPresence() {
    window.Echo.join("user.1")
        .listen('.user-presence', (e1) => {
            $('#member-loading').hide()
            $('#loading-members').hide()

            var e = e1.data;

            var userDoc = document.getElementById('member-' + e.user.username);

            if (userDoc != null || userDoc !== undefined) {
                $('#member-' + e.user.username).remove()
            }

            $('#membersList').append(this.member(e.user.username, e.user.roles, e.user.avatarURL, e.payload.slug))
        })
        .joining(member => {
            const userData = JSON.parse(JSON.stringify(member.data));
            $('#membersList').append(this.member(userData.user.username, userData.user.roles, userData.user.avatarURL, userData.payload.slug))
        })
        .leaving(member => {
            const userData = JSON.parse(JSON.stringify(member.data));
            $('#member-' + userData.user.username).remove()
        })
}

function member(username, roles, avatar, status) {
    return `<div class="profile-container d-flex align-items-stretch p-3 rounded lh-sm position-relative overflow-hidden" id="member-${username}">
                <img id="member-${username}-avatar" src="${avatar}"  alt="${username}" class="thumb-sm avatar b me-3" type="image/*">

                <small class="d-flex flex-column" style="line-height: 16px;">
                    <span class="text-ellipsis text-white" id="member-${username}-title">${username}</span>
                    <span class="text-ellipsis text-muted" id="member-${username}-subtitle">${roles}</span>
                    <span class="text-ellipsis text-muted" id="member-${username}-status">${status}</span>
                </small>
            </div>
            `;
}
