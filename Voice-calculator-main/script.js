// Initialize speech recognition
const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
recognition.lang = 'en-US';
recognition.continuous = false; // Stop automatically after a command is recognized

// Function to start the voice recognition for calculator or graph plotting
function startVoiceCommand() {
    recognition.start();
}

// Process the voice command when recognition completes
recognition.onresult = function(event) {
    const spokenCommand = event.results[0][0].transcript.toLowerCase();
    console.log("Recognized Command:", spokenCommand);

    // Check if the command is for plotting a graph
    if (spokenCommand.includes("plot graph")) {
        const graphInput = spokenCommand.replace("plot graph of ", "");
        
        // Parse function and range (e.g., "plot graph of sin(x) from -10 to 10")
        const functionMatch = graphInput.match(/([a-z]+\([x]+\)|[x]+\^[0-9]+)/); // e.g., sin(x) or x^2
        const rangeMatch = graphInput.match(/from\s(-?\d+)\sto\s(-?\d+)/);

        if (functionMatch && rangeMatch) {
            const functionInput = functionMatch[0];
            const rangeStart = parseInt(rangeMatch[1]);
            const rangeEnd = parseInt(rangeMatch[2]);

            // Populate form fields for graph plotting
            document.getElementById("function").value = functionInput;
            document.getElementById("rangeStart").value = rangeStart;
            document.getElementById("rangeEnd").value = rangeEnd;

            // Trigger the plot
            plotGraph();
        } else {
            alert("Could not recognize the function or range. Please try again.");
        }
    } else if (spokenCommand.includes("calculate")) {
        // If the command is a calculation (e.g., "calculate 5 plus 3 times 2")
        const expression = spokenCommand.replace("calculate", "").trim();
        document.getElementById("expression").value = expression;
        document.querySelector("form").submit(); // Submit the form
    } else {
        // Assume it's a regular calculation command if no keywords are detected
        document.getElementById("expression").value = spokenCommand;
    }
};

// Handle errors in voice recognition
recognition.onerror = function(event) {
    alert("Voice recognition error: " + event.error);
};

// Function to plot graph using Chart.js
async function plotGraph() {
    const functionInput = document.getElementById("function").value;
    const rangeStart = document.getElementById("rangeStart").value;
    const rangeEnd = document.getElementById("rangeEnd").value;

    const formData = new FormData();
    formData.append("function", functionInput);
    formData.append("rangeStart", rangeStart);
    formData.append("rangeEnd", rangeEnd);

    const response = await fetch("plot.php", {
        method: "POST",
        body: formData
    });
    const dataPoints = await response.json();

    const ctx = document.getElementById("graphCanvas").getContext("2d");

    // Destroy any existing chart instance
    if (window.chart) {
        window.chart.destroy();
    }

    // Create a new chart
    window.chart = new Chart(ctx, {
        type: "line",
        data: {
            datasets: [{
                label: `f(x) = ${functionInput}`,
                data: dataPoints,
                fill: false,
                borderColor: "blue",
                tension: 0.1
            }]
        },
        options: {
            scales: {
                x: { type: "linear", position: "bottom" },
                y: { type: "linear" }
            }
        }
    });
}
