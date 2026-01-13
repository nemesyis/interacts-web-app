@extends('layouts.teacher')

@section('title', 'Edit Quiz')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('teacher.appointments', $quiz->appointment->classroom_id) }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i>Back to Appointments
            </a>
            <h1 class="h3 mb-0">Edit Quiz</h1>
            <p class="text-muted">{{ $quiz->appointment->appointment_title }} • {{ $quiz->appointment->classroom->classroom_name }}</p>
        </div>
    </div>

    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Note:</strong> Editing the quiz will update it for all students. Any existing quiz attempts will remain unchanged.
    </div>

    <form method="POST" action="{{ route('teacher.quiz.update', $quiz->quiz_id) }}" id="quizForm">
        @csrf
        @method('PUT')

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
                                    class="form-control" 
                                    id="quiz_title" 
                                    name="quiz_title" 
                                    value="{{ old('quiz_title', $quiz->quiz_title) }}" 
                                    required
                                >
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea 
                                    class="form-control" 
                                    id="description" 
                                    name="description" 
                                    rows="2"
                                >{{ old('description', $quiz->description) }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="time_limit_minutes" class="form-label">Time Limit (minutes)</label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="time_limit_minutes" 
                                    name="time_limit_minutes" 
                                    value="{{ old('time_limit_minutes', $quiz->time_limit_minutes) }}"
                                >
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="passing_score" class="form-label">Passing Score</label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="passing_score" 
                                    name="passing_score" 
                                    value="{{ old('passing_score', $quiz->passing_score) }}"
                                    step="0.01"
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
                            <!-- Existing questions will be loaded here -->
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle me-2"></i>Update Quiz
                    </button>
                    <button type="button" class="btn btn-danger btn-lg" onclick="deleteQuiz()">
                        <i class="bi bi-trash me-2"></i>Delete Quiz
                    </button>
                    <a href="{{ route('teacher.appointments', $quiz->appointment->classroom_id) }}" class="btn btn-outline-secondary btn-lg">
                        Cancel
                    </a>
                </div>
            </div>

            <!-- Tips Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm bg-light sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-info-circle text-info me-2"></i>Editing Tips
                        </h6>
                        <ul class="small mb-0">
                            <li class="mb-2">Changes affect all students</li>
                            <li class="mb-2">Existing attempts are not modified</li>
                            <li class="mb-2">You can add or remove questions</li>
                            <li class="mb-2">Points will be recalculated</li>
                            <li>Delete quiz if you want to start over</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Form -->
    <form method="POST" action="{{ route('teacher.quiz.delete', $quiz->quiz_id) }}" id="deleteForm" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection

@section('scripts')
<script>
let questionCount = 0;
const existingQuestions = @json($quiz->questions);

// Load existing questions on page load
window.addEventListener('load', function() {
    existingQuestions.forEach((question, index) => {
        addQuestion(question);
    });
});

function addQuestion(existingData = null) {
    questionCount++;
    const container = document.getElementById('questions-container');

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
                    <select class="form-select" name="questions[${questionCount}][type]" data-qid="${questionCount}" required>
                        <option value="">Select type...</option>
                        <option value="multiple_choice" ${existingData && existingData.question_type === 'multiple_choice' ? 'selected' : ''}>Multiple Choice</option>
                        <option value="true_false" ${existingData && existingData.question_type === 'true_false' ? 'selected' : ''}>True/False</option>
                        <option value="short_answer" ${existingData && existingData.question_type === 'short_answer' ? 'selected' : ''}>Short Answer</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Question Text <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="questions[${questionCount}][text]" rows="2" required>${existingData ? existingData.question_text : ''}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Points <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="questions[${questionCount}][points]" value="${existingData ? existingData.points : 1}" min="0.01" step="0.01" required>
                </div>

                <div id="options-${questionCount}"></div>
                <div id="answer-${questionCount}"></div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', questionHtml);
    const select = document.querySelector(`#question-${questionCount} select`);
    updateQuestionType(questionCount, select.value, existingData);

    // If existing data, populate fields
    if (existingData) {
    updateQuestionType(questionCount, existingData.question_type, existingData);
    }
}

function removeQuestion(id) {
    if (confirm('Remove this question?')) {
        document.getElementById(`question-${id}`).remove();
    }
}

function updateQuestionType(questionId, type, existingData = null) {
    const optionsDiv = document.getElementById(`options-${questionId}`);
    const answerDiv = document.getElementById(`answer-${questionId}`);

    if (type === 'multiple_choice') {
        const options = existingData && existingData.options ? existingData.options : ['', ''];
        
        optionsDiv.innerHTML = `
            <label class="form-label">Answer Options <span class="text-danger">*</span></label>
            <div id="mc-options-${questionId}">
                ${options.map((opt, idx) => `
                    <div class="input-group mb-2">
                        <span class="input-group-text">${String.fromCharCode(65 + idx)}</span>
                        <input type="text" class="form-control" name="questions[${questionId}][options][]" value="${opt}" required>
                        ${idx > 1 ? '<button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>' : ''}
                    </div>
                `).join('')}
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="addOption(${questionId})">
                <i class="bi bi-plus"></i> Add Option
            </button>
        `;
        
        answerDiv.innerHTML = `
            <label class="form-label">Correct Answer <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="questions[${questionId}][answer]" value="${existingData ? existingData.correct_answer : ''}" required>
        `;
    } else if (type === 'true_false') {
        optionsDiv.innerHTML = '';
        answerDiv.innerHTML = `
            <label class="form-label">Correct Answer <span class="text-danger">*</span></label>
            <select class="form-select" name="questions[${questionId}][answer]" required>
                <option value="True" ${existingData && existingData.correct_answer === 'True' ? 'selected' : ''}>True</option>
                <option value="False" ${existingData && existingData.correct_answer === 'False' ? 'selected' : ''}>False</option>
            </select>
        `;
    } else if (type === 'short_answer') {
        optionsDiv.innerHTML = '';
        answerDiv.innerHTML = `
            <label class="form-label">Correct Answer <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="questions[${questionId}][answer]" value="${existingData ? existingData.correct_answer : ''}" required>
        `;
    }
}

function addOption(questionId) {
    const container = document.getElementById(`mc-options-${questionId}`);
    const count = container.children.length;
    const letter = String.fromCharCode(65 + count);
    
    const html = `
        <div class="input-group mb-2">
            <span class="input-group-text">${letter}</span>
            <input type="text" class="form-control" name="questions[${questionId}][options][]" required>
            <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                <i class="bi bi-x"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}

function deleteQuiz() {
    if (confirm('Are you sure you want to delete this quiz? This action cannot be undone!')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endsection