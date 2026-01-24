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
let allTasks = []; // Global variable to store tasks

async function loadTasks(){
    const tasks = await post({action:"get_tasks"});
    allTasks = tasks; // Store the fetched tasks
    renderTasks(allTasks); // Call a separate function to draw them
}

function renderTasks(tasksToRender) {
    // 1. Get references to your three containers
    const assignedList = document.getElementById("assignedList");
    const lateList = document.getElementById("lateList");
    const turnedinList = document.getElementById("turnedinList");

    // 2. Clear all containers before re-rendering
    assignedList.innerHTML = "";
    lateList.innerHTML = "";
    turnedinList.innerHTML = "";

    tasksToRender.forEach(t => {
        const color = subjectColors[t.subject] || "#777";
        
        // 3. Logic for Dates
        const dueDate = new Date(t.due_date);
        const now = new Date();
        // Set 'now' to the start of today if you only care about the date, not the exact time
        now.setHours(0, 0, 0, 0); 
        
        const isOverdue = !t.is_done && dueDate < now;

        // 4. Create the Element
        const div = document.createElement("div");
        div.className = `assignment ${isOverdue ? 'red' : ''}`;
        div.style.borderLeft = `6px solid ${color}`;

        div.innerHTML = `
            <span>
                <span class="subject-label" style="background:${color}">
                    ${t.subject}
                </span>
                <strong>${t.title}</strong> <br>
                <small>Due: ${t.due_date} | Priority: ${t.priority}</small>
            </span>
            <div style="position:relative; display:inline-block;">
                ${!t.is_done 
                    ? `<button onclick="doneTask(${t.id})">Turn In</button>`
                    : '<span class="status-done">Completed</span>'}
                <button class="more-btn" onclick="toggleMenu(this)">⋮</button>
                <div class="dropdown-menu" style="display:none; position:absolute; right:0; top:30px; background:#fff; color:#000; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.15); min-width:100px; z-index:10;">
                    <div style="padding:8px; cursor:pointer;" onclick="deleteTask(${t.id})">Delete</div>
                </div>
            </div>
        `;

        // 5. Route the task to the correct container
        if (t.is_done) {
            // Task is finished
            turnedinList.appendChild(div);
        } else if (isOverdue) {
            // Task is NOT finished and date has passed
            lateList.appendChild(div);
        } else {
            // Task is NOT finished and date is in the future
            assignedList.appendChild(div);
        }
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



function sortTasks(criteria) {
    allTasks.sort((a, b) => {
        if (criteria === 'subject') {
            return a.subject.localeCompare(b.subject);
        } 
        else if (criteria === 'due_date') {
            return new Date(a.due_date) - new Date(b.due_date);
        } 
        else if (criteria === 'priority') {
            const weights = { 'High': 1, 'Medium': 2, 'Low': 3 };
            return (weights[a.priority] || 4) - (weights[b.priority] || 4);
        }
    });
    
    renderTasks(allTasks); 
}
/* ========= INIT ========= */
loadSubjects();
loadTasks();