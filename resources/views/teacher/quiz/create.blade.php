@extends('layouts.teacher')

@section('title', 'Create Quiz')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('teacher.appointments', $appointment->classroom_id) }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to Appointments
            </a>
            <h1 class="h3 mb-0">Create Quiz</h1>
            <p class="text-muted">{{ $appointment->appointment_title }} • {{ $appointment->classroom->classroom_name }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('teacher.quiz.store', $appointment->appointment_id) }}" id="quizForm">
        @csrf

        <div class="row">
            <!-- Quiz Settings -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Quiz Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="quiz_title" class="form-label">Quiz Title <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    class="form-control @error('quiz_title') is-invalid @enderror" 
                                    id="quiz_title" 
                                    name="quiz_title" 
                                    value="{{ old('quiz_title', $appointment->appointment_title . ' - Quiz') }}" 
                                    required
                                >
                                @error('quiz_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description <span class="text-muted">(Optional)</span></label>
                                <textarea 
                                    class="form-control" 
                                    id="description" 
                                    name="description" 
                                    rows="2"
                                >{{ old('description') }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="time_limit_minutes" class="form-label">Time Limit (minutes) <span class="text-muted">(Optional)</span></label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="time_limit_minutes" 
                                    name="time_limit_minutes" 
                                    min="1" 
                                    max="180"
                                    value="{{ old('time_limit_minutes') }}"
                                    placeholder="Leave empty for no limit"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="passing_score" class="form-label">Passing Score <span class="text-muted">(Optional)</span></label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="passing_score" 
                                    name="passing_score" 
                                    min="0" 
                                    step="0.01"
                                    value="{{ old('passing_score') }}"
                                    placeholder="Leave empty for no passing score"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Questions Section -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Questions</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addQuestion()">
                            <i class="bi bi-plus-circle me-1"></i>Add Question
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="questions-container">
                            <!-- Questions will be added here -->
                        </div>

                        <div class="text-center py-4" id="no-questions-message">
                            <i class="bi bi-question-circle fs-1 text-muted d-block mb-2"></i>
                            <p class="text-muted">No questions added yet. Click "Add Question" to start.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle me-2"></i>Create Quiz
                    </button>
                    <a href="{{ route('teacher.appointments', $appointment->classroom_id) }}" class="btn btn-outline-secondary btn-lg">
                        Cancel
                    </a>
                </div>
            </div>

            <!-- Tips Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm bg-light sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-lightbulb text-warning me-2"></i>Tips for Creating Quizzes
                        </h6>
                        <ul class="small mb-0">
                            <li class="mb-2">Add at least one question</li>
                            <li class="mb-2">Each question must have a correct answer</li>
                            <li class="mb-2">For multiple choice, provide 2-5 options</li>
                            <li class="mb-2">Points determine the question's weight</li>
                            <li class="mb-2">Students can only take the quiz once</li>
                            <li class="mb-2">Quiz is auto-graded based on correct answers</li>
                            <li>Set a passing score to determine pass/fail</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
let questionCount = 0;

function addQuestion() {
    questionCount++;
    const container = document.getElementById('questions-container');
    const noQuestionsMsg = document.getElementById('no-questions-message');
    
    if (noQuestionsMsg) {
        noQuestionsMsg.style.display = 'none';
    }

    const questionHtml = `
        <div class="card mb-3 question-card" id="question-${questionCount}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Question ${questionCount}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeQuestion(${questionCount})">
                    <i class="bi bi-trash"></i> Remove
                </button>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Question Type <span class="text-danger">*</span></label>
                    <select class="form-select" name="questions[${questionCount}][type]" onchange="updateQuestionType(${questionCount}, this.value)" required>
                        <option value="">Select type...</option>
                        <option value="multiple_choice">Multiple Choice</option>
                        <option value="true_false">True/False</option>
                        <option value="short_answer">Short Answer</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Question Text <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="questions[${questionCount}][text]" rows="2" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Points <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="questions[${questionCount}][points]" value="1" min="0.01" step="0.01" required>
                </div>

                <div id="options-${questionCount}" style="display: none;">
                    <!-- Options will be added here for multiple choice -->
                </div>

                <div id="answer-${questionCount}">
                    <!-- Answer input will be added based on type -->
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', questionHtml);
}

function removeQuestion(id) {
    if (confirm('Are you sure you want to remove this question?')) {
        document.getElementById(`question-${id}`).remove();
        
        // Check if no questions left
        const remainingQuestions = document.querySelectorAll('.question-card');
        if (remainingQuestions.length === 0) {
            document.getElementById('no-questions-message').style.display = 'block';
        }
    }
}

function updateQuestionType(questionId, type) {
    const optionsDiv = document.getElementById(`options-${questionId}`);
    const answerDiv = document.getElementById(`answer-${questionId}`);

    if (type === 'multiple_choice') {
        optionsDiv.style.display = 'block';
        optionsDiv.innerHTML = `
            <label class="form-label">Answer Options <span class="text-danger">*</span></label>
            <div id="mc-options-${questionId}">
                <div class="input-group mb-2">
                    <span class="input-group-text">A</span>
                    <input type="text" class="form-control" name="questions[${questionId}][options][]" placeholder="Option A" required>
                </div>
                <div class="input-group mb-2">
                    <span class="input-group-text">B</span>
                    <input type="text" class="form-control" name="questions[${questionId}][options][]" placeholder="Option B" required>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="addOption(${questionId})">
                <i class="bi bi-plus"></i> Add Option
            </button>
        `;
        
        answerDiv.innerHTML = `
            <label class="form-label">Correct Answer <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="questions[${questionId}][answer]" placeholder="Type the exact correct answer" required>
            <small class="text-muted">Must match one of the options exactly</small>
        `;
    } else if (type === 'true_false') {
        optionsDiv.style.display = 'none';
        optionsDiv.innerHTML = '';
        
        answerDiv.innerHTML = `
            <label class="form-label">Correct Answer <span class="text-danger">*</span></label>
            <select class="form-select" name="questions[${questionId}][answer]" required>
                <option value="">Select...</option>
                <option value="True">True</option>
                <option value="False">False</option>
            </select>
        `;
    } else if (type === 'short_answer') {
        optionsDiv.style.display = 'none';
        optionsDiv.innerHTML = '';
        
        answerDiv.innerHTML = `
            <label class="form-label">Correct Answer <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="questions[${questionId}][answer]" placeholder="Type the correct answer" required>
            <small class="text-muted">Answer matching is case-insensitive</small>
        `;
    } else {
        optionsDiv.style.display = 'none';
        optionsDiv.innerHTML = '';
        answerDiv.innerHTML = '';
    }
}

function addOption(questionId) {
    const container = document.getElementById(`mc-options-${questionId}`);
    const optionCount = container.children.length;
    const letters = ['C', 'D', 'E', 'F', 'G', 'H'];
    
    if (optionCount < 8) {
        const letter = letters[optionCount - 2] || String.fromCharCode(65 + optionCount);
        const optionHtml = `
            <div class="input-group mb-2">
                <span class="input-group-text">${letter}</span>
                <input type="text" class="form-control" name="questions[${questionId}][options][]" placeholder="Option ${letter}" required>
                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', optionHtml);
    } else {
        alert('Maximum 8 options allowed');
    }
}

// Validate form before submit
document.getElementById('quizForm').addEventListener('submit', function(e) {
    const questions = document.querySelectorAll('.question-card');
    
    if (questions.length === 0) {
        e.preventDefault();
        alert('Please add at least one question to the quiz.');
        return false;
    }
});

// Add first question on page load
window.addEventListener('load', function() {
    addQuestion();
});
</script>
@endsection