async function post(data){
    return fetch("Assignment.php", {
        method:"POST",
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:new URLSearchParams(data)
    }).then(res=>res.json());
}


let subjectColors = {};

async function loadSubjects(){
    const subjects = await post({action:"get_subjects"});

    subjectSelect.innerHTML = "";
    subjectList.innerHTML = "";
    subjectColors = {};

    subjects.forEach(s=>{
        subjectColors[s.name] = s.color;

        subjectSelect.innerHTML += `<option>${s.name}</option>`;
        subjectList.innerHTML += `
        <div class="subject">
            <span style="background:${s.color};padding:4px 10px;border-radius:8px">
                ${s.name}
            </span>
            <button onclick="deleteSubject(${s.id})">Delete</button>
        </div>`;
    });
}

async function addSubject(){
    const name = newSubjectName.value.trim();
    if(!name) return alert("Enter subject name");

    await post({
        action:"add_subject",
        name,
        color:newSubjectColor.value
    });

    newSubjectName.value="";
    closeSubjectPopup();
    loadSubjects();
    loadTasks(); // refresh tasks to get colors
}

async function deleteSubject(id){
    await post({action:"delete_subject",id});
    loadSubjects();
    loadTasks();
}

/* ========= TASKS ========= */
async function loadTasks(){
    const tasks = await post({action:"get_tasks"});
    assignedList.innerHTML = "";

    tasks.forEach(t=>{
        const color = subjectColors[t.subject] || "#777";
        const overdue = !t.is_done && new Date(t.due_date) < new Date();

        const div = document.createElement("div");
        div.className = `assignment ${overdue ? 'red' : ''}`;
        div.style.borderLeft = `6px solid ${color}`;

        div.innerHTML = `
            <span>
                <span class="subject-label" style="background:${color}">
                    ${t.subject}
                </span>
                ${t.title} (${t.due_date}) [${t.priority}]
            </span>
            <div style="position:relative; display:inline-block;">
                ${!t.is_done 
                    ? `<button onclick="doneTask(${t.id})">Turn In</button>`
                    : 'Turned In'}
                <button class="more-btn" onclick="toggleMenu(this)">⋮</button>
                <div class="dropdown-menu" style="display:none; position:absolute; right:0; top:30px; background:#fff; color:#000; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.15); min-width:100px; z-index:10;">
                    <div style="padding:8px; cursor:pointer;" onclick="deleteTask(${t.id})">Delete</div>
                </div>
            </div>
        `;

        assignedList.appendChild(div);
    });
}

async function addTask(){
    if(!taskInput.value || !subjectSelect.value || !dueDate.value)
        return alert("Fill all fields");

    await post({
        action:"add_task",
        title:taskInput.value,
        subject:subjectSelect.value,
        priority:prioritySelect.value,
        due_date:dueDate.value
    });

    taskInput.value="";
    dueDate.value="";
    loadTasks();
}

async function doneTask(id){
    await post({action:"done_task",id});
    loadTasks();
}

async function deleteTask(id){
    await post({action:"delete_task",id});
    loadTasks();
}

/* ========= POPUP ========= */
function openSubjectPopup(){
    subjectOverlay.classList.add("active");
    subjectCard.classList.add("active");
}

function closeSubjectPopup(){
    subjectOverlay.classList.remove("active");
    subjectCard.classList.remove("active");
}

function toggleMenu(button){
    const menu = button.nextElementSibling;
    const allMenus = document.querySelectorAll('.dropdown-menu');
    allMenus.forEach(m => { if(m !== menu) m.style.display = 'none'; });
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}

// Close dropdown if clicked outside
document.addEventListener('click', e => {
    if(!e.target.classList.contains('more-btn')){
        document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display='none');
    }
});

function switchPage(page) {
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    event.target.closest('.nav-item').classList.add('active');
    loadPage(page);
}

function loadPage(page) {
    const contentArea = document.getElementById('contentArea');
    
    const pages = {
        home: `
            <div class="page-header">
                <h1>Welcome to Edu-gram, ${currentUser.name}! 🎉</h1>
                <p>Your personalized dashboard to manage your studies effectively.</p>
            </div>
        `,
        assignments: `
            <div class="page-header">
                <h1>Assignment Tracker 📝</h1>
                <p>Manage and track your assignments</p>
            </div>
            <div class="content-placeholder">
                <i class="fas fa-tasks placeholder-icon"></i>
                <p>Assignment tracking features coming soon...</p>
            </div>
        `,
        exams: `
            <div class="page-header">
                <h1>Exam Tracker 🎓</h1>
                <p>Keep track of your upcoming exams</p>
            </div>
            <div class="content-placeholder">
                <i class="fas fa-graduation-cap placeholder-icon"></i>
                <p>Exam tracking features coming soon...</p>
            </div>
        `,
        pomodoro: `
            <div class="page-header">
                <h1>Pomodoro Timer ⏰</h1>
                <p>Boost your productivity with focused study sessions</p>
            </div>
            <div class="content-placeholder">
                <i class="fas fa-clock placeholder-icon"></i>
                <p>Pomodoro timer coming soon...</p>
            </div>
        `,
        todo: `
            <div class="page-header">
                <h1>To-Do List ✅</h1>
                <p>Organize your daily tasks</p>
            </div>
            <div class="content-placeholder">
                <i class="fas fa-list-check placeholder-icon"></i>
                <p>To-Do list features coming soon...</p>
            </div>
        `,
        profile: `
            <div class="page-header">
                <h1>Profile 👤</h1>
                <p>Manage your account settings</p>
            </div>
            <div class="profile-card">
                <div class="profile-avatar-large">
                    <i class="fas fa-user"></i>
                </div>
                <h2>${currentUser.name}</h2>
                <p>${currentUser.email}</p>
            </div>
        `,
        help: `
            <div class="page-header">
                <h1>Help & Support 🆘</h1>
                <p>Get assistance and learn how to use Edu-gram</p>
            </div>
            <div class="content-placeholder">
                <i class="fas fa-question-circle placeholder-icon"></i>
                <p>Help documentation coming soon...</p>
            </div>
        `
    };
    
    contentArea.innerHTML = pages[page] || pages.home;
}
/* ========= INIT ========= */
loadSubjects();
loadTasks();