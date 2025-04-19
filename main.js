let systemsDiv = document.getElementById("systems");
let activeSessions = JSON.parse(localStorage.getItem("activeSessions") || "{}");

fetch("php/system.php?action=list")
  .then(res => res.json())
  .then(data => {
    data.forEach(system => {
      let container = document.createElement("div");
      container.className = "system";

      let title = document.createElement("h2");
      title.textContent = system.name;
      container.appendChild(title);

      let timeDisplay = document.createElement("p");
      let costDisplay = document.createElement("p");
      container.appendChild(timeDisplay);
      container.appendChild(costDisplay);

      const startBtn = document.createElement("button");
      startBtn.textContent = "شروع";
      container.appendChild(startBtn);

      const endBtn = document.createElement("button");
      endBtn.textContent = "پایان";
      container.appendChild(endBtn);

      const detailBtn = document.createElement("button");
      detailBtn.textContent = "جزئیات";
      detailBtn.onclick = () => {
        window.location.href = `php/details.php?id=${system.id}`;
      };
      container.appendChild(detailBtn);

      startBtn.onclick = () => startSession(system, timeDisplay, costDisplay, endBtn, startBtn);
      endBtn.onclick = () => endSession(system, timeDisplay, costDisplay, endBtn, startBtn);

      systemsDiv.appendChild(container);

      if (activeSessions[system.id]) {
        startCountdown(system, timeDisplay, costDisplay, endBtn, startBtn);
      }
    });
  });

function startSession(system, timeDisplay, costDisplay, endBtn, startBtn) {
  if (activeSessions[system.id]) {
    alert("تایمر این سیستم در حال اجراست.");
    return;
  }

  const minutes = prompt("مدت زمان (دقیقه):");
  if (!minutes || isNaN(minutes)) return;

  const seconds = parseInt(minutes) * 60;
  const start = Date.now();

  activeSessions[system.id] = {
    start: new Date(start).toISOString(),
    seconds
  };

  localStorage.setItem("activeSessions", JSON.stringify(activeSessions));

  startBtn.disabled = true;
  endBtn.disabled = false;

  startCountdown(system, timeDisplay, costDisplay, endBtn, startBtn);
}

function startCountdown(system, timeDisplay, costDisplay, endBtn, startBtn) {
  const session = activeSessions[system.id];
  if (!session) return;

  const costPerSec = parseFloat(system.cost_per_second);
  const interval = setInterval(() => {
    const now = Date.now();
    const start = new Date(session.start).getTime();
    const elapsed = Math.floor((now - start) / 1000);
    const remaining = session.seconds - elapsed;

    if (remaining <= 0) {
      clearInterval(interval);

      const amount = Math.floor(session.seconds * costPerSec);
      timeDisplay.textContent = "زمان به پایان رسید!";
      costDisplay.textContent = `مبلغ نهایی: ${amount} تومان`;
      endBtn.disabled = true;

      // 🎵 صدا
      const audio = new Audio("sounds/alarm.mp3");
      audio.play();

      // 🔔 پیام ساده
      alert(`✅ زمان سیستم ${system.name} تمام شد!\n⏱ مدت: ${session.seconds} ثانیه\n💰 مبلغ: ${amount} تومان`);

      // ثبت
      fetch("php/payments.php?action=add", {
        method: "POST",
        body: JSON.stringify({
          system_id: system.id,
          start_time: session.start.slice(0, 19).replace("T", " "),
          end_time: new Date().toISOString().slice(0, 19).replace("T", " "),
          amount
        })
      }).then(() => {
        delete activeSessions[system.id];
        localStorage.setItem("activeSessions", JSON.stringify(activeSessions));
        startBtn.disabled = false;
      });

      return;
    }

    const mins = String(Math.floor(remaining / 60)).padStart(2, '0');
    const secs = String(remaining % 60).padStart(2, '0');
    timeDisplay.textContent = `زمان باقی‌مانده: ${mins}:${secs}`;

    const amount = Math.floor(Math.min(elapsed, session.seconds) * costPerSec);
    costDisplay.textContent = `مبلغ: ${amount} تومان`;
  }, 1000);
}

function endSession(system, timeDisplay, costDisplay, endBtn, startBtn) {
  const session = activeSessions[system.id];
  if (!session) {
    alert("این سیستم هنوز شروع نشده!");
    return;
  }

  const now = Date.now();
  const realUsed = Math.floor((now - new Date(session.start)) / 1000);
  const usedSeconds = Math.min(realUsed, session.seconds);
  const amount = Math.floor(usedSeconds * parseFloat(system.cost_per_second));

  fetch("php/payments.php?action=add", {
    method: "POST",
    body: JSON.stringify({
      system_id: system.id,
      start_time: session.start.slice(0, 19).replace("T", " "),
      end_time: new Date().toISOString().slice(0, 19).replace("T", " "),
      amount
    })
  }).then(() => {
    alert(`✅ ثبت شد!\n⏱ مدت: ${usedSeconds} ثانیه\n💰 مبلغ: ${amount} تومان`);
    delete activeSessions[system.id];
    localStorage.setItem("activeSessions", JSON.stringify(activeSessions));
    costDisplay.textContent = "";
    timeDisplay.textContent = "";
    endBtn.disabled = true;
    startBtn.disabled = false;
  });
}
