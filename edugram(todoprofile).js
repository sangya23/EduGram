window.onload = function () {

 
    const editBtn = document.getElementById('edit-btn');
    const saveBtn = document.getElementById('save-btn');

    const dobP = document.getElementById('dob');
    const educationP = document.getElementById('education');
    const majorP = document.getElementById('major');

    if (editBtn) {
        editBtn.addEventListener('click', () => {
           
            dobP.innerHTML = 'Date of Birth: <input type="date" id="dob-input">';
            educationP.innerHTML = 'Education: <input type="text" id="education-input">';
            majorP.innerHTML = 'Major Subject: <input type="text" id="major-input">';

            editBtn.style.display = 'none';
            saveBtn.style.display = 'inline-block';
        });

        saveBtn.addEventListener('click', () => {
            const dobInput = document.getElementById('dob-input').value;
            const educationInput = document.getElementById('education-input').value;
            const majorInput = document.getElementById('major-input').value;

            if (!validateDOB(dobInput)) return;

    
            fetch("saveprofile.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ 
                    dob: dobInput, 
                    education: educationInput, 
                    major: majorInput 
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    dobP.textContent = `Date of Birth: ${dobInput}`;
                    educationP.textContent = `Education: ${educationInput}`;
                    majorP.textContent = `Major Subject: ${majorInput}`;

                    editBtn.style.display = 'inline-block';
                    saveBtn.style.display = 'none';
                    location.reload(); 
                }
            });
        });
    }


    const taskList = document.getElementById('task-list');
    const addTaskBtn = document.getElementById('add-task-btn');
    const taskInput = document.getElementById('task-input');
    const deadlineInput = document.getElementById('deadline-input');


    if (taskList) {
        fetch("fetchtask.php")
            .then(res => res.json())
            .then(tasks => {
                taskList.innerHTML = ''; 
                tasks.forEach(t => {
                    addTaskToUI(t.id, t.task, t.due);
                });
            });
    }

    if (addTaskBtn) {
        addTaskBtn.addEventListener('click', () => {
            const task = taskInput.value.trim();
            const due = deadlineInput.value; 

            if (task === '') {
                alert("Task cannot be empty");
                return;
            }

            if (!validateDeadline(due)) return;

            fetch("addtask.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ task: task, due: due }) 
            })
            .then(res => res.json())
            .then(data => {
         
                if (data.id || data.success) {
                    addTaskToUI(data.id, task, due);
                    
                    taskInput.value = '';
                    deadlineInput.value = '';
                } else {
                    alert("Failed to save task. Please check if XAMPP MySQL is running.");
                }
            })
            .catch(err => alert("Connection error. Check XAMPP Control Panel."));
        });
    }


    function addTaskToUI(id, task, deadline) {
        const li = document.createElement('li');
        li.setAttribute("data-id", id);
        li.className = "task-item";

        const text = document.createElement('span');
        text.innerHTML = `<strong>${task}</strong> (Due: ${deadline || 'No deadline'})`;

        const delBtn = document.createElement('button');
        delBtn.textContent = "Delete";
        delBtn.className = "delete-btn";
        delBtn.style.marginLeft = "10px";

        delBtn.onclick = function () {
            deleteTask(id, li);
        };

        li.appendChild(text);
        li.appendChild(delBtn);
        taskList.appendChild(li);
    }


    function deleteTask(id, li) {
        fetch("deletetask.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                li.remove();
            }
        });
    }

    
    function validateDOB(dob) {
        if (!dob) return true;
        const selectedDate = new Date(dob);
        const today = new Date();
        if (selectedDate > today) {
            alert("Date of Birth cannot be in the future!");
            return false;
        }
        return true;
    }

    function validateDeadline(deadline) {
        if (!deadline) return true;
        const selectedDate = new Date(deadline);
        const today = new Date();
        if (selectedDate < today) {
            alert("Deadline cannot be in the past!");
            return false;
        }
        return true;
    }
};