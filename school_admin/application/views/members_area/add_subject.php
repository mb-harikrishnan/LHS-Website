<?php
$pageTitle = 'Add Subject Mapping';
$breadcrumb = 'Add Subject Mapping';
$activePage = 'add_subject';
$showGlobalSearch = false;
?>
<div class="card cd-form-card">
  <div class="card-head">
    <div class="card-title">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1e3a8a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 7V12h5" />
        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Z" />
        <path d="M12 12H12.01" />
        <path d="M16 16H16.01" />
      </svg>
      Add Subject Details
    </div>
    <button class="card-action" onclick="window.location.href='<?php echo base_url('subject_list'); ?>'">
      Subject List
    </button>
  </div>
  <form id="subjectMappingForm" method="post" action="<?php echo base_url('insert_subject_mapping'); ?>">
    <!-- Class Selector (Single Select) -->
     <div class="cd-row">

    <div class="cd-group">
      <label class="cd-label" for="cmId">Select Class</label>
      <select name="cmId" id="cmId" class="cd-select">
        <option value="">-- Select Class --</option>
        <?php if(!empty($classes)){ foreach($classes as $class){ ?>
          <option value="<?php echo $class->cmId; ?>"><?php echo $class->cmName; ?></option>
        <?php } } ?>
      </select>
    </div>
    <!-- Exam Selector (Single Select) -->
    <div class="cd-group">
      <label class="cd-label" for="emId">Select Exam</label>
      <select name="emId" id="emId" class="cd-select">
        <option value="">-- Select Exam --</option>
        <?php if(!empty($exams)){ foreach($exams as $exam){ 
          $examId = isset($exam->emId) ? $exam->emId : (isset($exam->examId) ? $exam->examId : (isset($exam->id) ? $exam->id : ''));
          $examName = isset($exam->emName) ? $exam->emName : (isset($exam->examName) ? $exam->examName : (isset($exam->name) ? $exam->name : ''));
        ?>
          <option value="<?php echo $examId; ?>"><?php echo $examName; ?></option>
        <?php } } ?>
      </select>
    </div>
     </div>
    <!-- Multi-select Subject Selector with Chips Above -->
    <div class="cd-group">
      <label class="cd-label">Select Subjects</label>
      
      <!-- Selected Chips Container (Upper the Selector) -->
      <div id="selectedSubjectsChips" class="cd-chips-container">
        <!-- Dynamic chips will be inserted here by JavaScript -->
        <span class="cd-no-selection">No subjects selected</span>
      </div>
      <!-- Custom Multi-select Dropdown Container -->
      <div class="cd-multiselect-wrapper" id="subjectMultiselectWrapper">
        <div class="cd-multiselect-trigger" id="subjectSelectTrigger">
          <span class="cd-trigger-placeholder">Select Subjects...</span>
          <span class="cd-trigger-count" style="display: none;">0 selected</span>
          <svg class="cd-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"></polyline>
          </svg>
        </div>
        <div class="cd-multiselect-dropdown" id="subjectDropdown">
          <div class="cd-dropdown-search">
            <input type="text" id="subjectSearchInput" placeholder="Search subjects..." autocomplete="off">
            <i class="fa fa-search cd-search-icon"></i>
          </div>
          <div class="cd-dropdown-options" id="subjectOptionsList">
            <?php if(!empty($subjects)){ foreach($subjects as $subject){ 
              $subjectId = isset($subject->smId) ? $subject->smId : (isset($subject->subjectId) ? $subject->subjectId : (isset($subject->id) ? $subject->id : ''));
              $subjectName = isset($subject->smName) ? $subject->smName : (isset($subject->subjectName) ? $subject->subjectName : (isset($subject->name) ? $subject->name : ''));
            ?>
              <label class="cd-dropdown-option">
                <input type="checkbox" name="smId[]" value="<?php echo $subjectId; ?>" data-name="<?php echo $subjectName; ?>">
                <span class="cd-custom-checkbox"></span>
                <span class="cd-option-text"><?php echo $subjectName; ?></span>
              </label>
            <?php } } else { ?>
              <div class="cd-no-options">No subjects found. Add a subject first.</div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <!-- Marks Input Field -->
    <div class="cd-group">
      <label class="cd-label" for="marks">Marks</label>
      <input type="number" name="marks" id="marks" class="cd-input" placeholder="Enter marks (e.g. 100)" min="0" max="1000">
    </div>
    <!-- Form Submit Action -->
    <div class="cd-btn-row">
      <button type="submit" class="cd-submit">Save Mapping</button>
    </div>
  </form>
</div>
<style>
/* Modern typography and styling for Subject Mapping Form to match School Management Portal */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
.cd-form-card {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background: #ffffff;
    border-radius: 24px;
    padding: 45px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02), 0 10px 40px rgba(0, 0, 0, 0.04);
    border: 1px solid #eef2f7;
    max-width: 900px;
    margin: 40px auto;
        width:95%;

}

.cd-row{
    display:flex;
    gap:25px;
}

.cd-row .cd-group{
    flex:1;
}

@media(max-width:768px){

.cd-row{
    flex-direction:column;
}

}
.card-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
    border-bottom: 1.5px solid #f1f5f9;
    padding-bottom: 18px;
}
.card-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
}
.card-title svg {
    color: #1e3a8a;
}
.card-action {
    border: none;
    outline: none;
    background: #f1f5f9;
    color: #475569;
    padding: 8px 18px;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
    font-size: 13.5px;
    transition: all 0.2s ease;
}
.card-action:hover {
    background: #e2e8f0;
    color: #1e293b;
}
.cd-form-card .cd-group {
    margin-bottom: 24px;
    position: relative;
}
.cd-form-card .cd-label {
    display: block;
    margin-bottom: 8px;
    font-size: 13.5px;
    font-weight: 600;
    color: #475569 !important;
}
/* Custom Select, trigger, and Input Styling matching screenshot fields */
.cd-form-card .cd-select,
.cd-form-card .cd-input,
.cd-multiselect-trigger {
    width: 100%;
    height: 56px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    border-radius: 12px;
    padding: 0 16px;
    font-size: 14.5px;
    color: #1e293b;
    outline: none;
    box-sizing: border-box;
    font-family: inherit;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    appearance: none; /* remove default arrow */
    -webkit-appearance: none;
}
/* Add custom arrow for raw selects */
.cd-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 14px center;
    background-repeat: no-repeat;
    background-size: 18px;
    padding-right: 40px !important;
}
.cd-form-card .cd-select:focus,
.cd-form-card .cd-input:focus,
.cd-multiselect-wrapper.cd-active .cd-multiselect-trigger {
    background: #ffffff;
    border-color: #1e3a8a;
    box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.08);
}
/* Chips Container (Styled nicely to display above the selector) */
.cd-chips-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    min-height: 48px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    border-radius: 12px;
    padding: 10px 14px;
    margin-bottom: 8px;
    align-items: center;
    box-sizing: border-box;
    transition: all 0.2s ease;
}
.cd-chips-container:has(.cd-chip-item) {
    background: #ffffff;
    border-color: #cbd5e1;
}
.cd-no-selection {
    font-size: 16px;
    color: #94a3b8;
    font-style: italic;
}
/* Chips Style matching image */
.cd-chip-item {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e40af;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    animation: scaleUp 0.15s ease-out;
}
.cd-chip-remove {
    background: none;
    border: none;
    color: #3b82f6;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    transition: all 0.15s ease;
}
.cd-chip-remove:hover {
    color: #1d4ed8;
}
/* Custom Multi-select Dropdown wrapper and items */
.cd-multiselect-wrapper {
    position: relative;
    width: 100%;
}
.cd-trigger-placeholder {
    color: #64748b;
}
.cd-trigger-count {
    background: #1e3a8a;
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
}
.cd-chevron {
    color: #64748b;
    transition: transform 0.2s ease;
}
.cd-multiselect-wrapper.cd-active .cd-chevron {
    transform: rotate(180deg);
}
.cd-multiselect-dropdown {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    width: 100%;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
    z-index: 1000;
    display: none;
    box-sizing: border-box;
}
.cd-dropdown-search {
    position: relative;
    padding: 10px;
    border-bottom: 1px solid #f1f5f9;
}
.cd-dropdown-search input {
    width: 100%;
    height: 38px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0 12px 0 34px;
    font-size: 13.5px;
    outline: none;
    box-sizing: border-box;
    font-family: inherit;
    transition: border-color 0.2s ease;
}
.cd-dropdown-search input:focus {
    border-color: #1e3a8a;
}
.cd-search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 13px;
}
.cd-dropdown-options {
    max-height: 200px;
    overflow-y: auto;
    padding: 6px;
}
.cd-dropdown-options::-webkit-scrollbar {
    width: 5px;
}
.cd-dropdown-options::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}
.cd-dropdown-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    color: #475569;
    transition: all 0.1s ease;
    user-select: none;
}
.cd-dropdown-option:hover {
    background: #f8fafc;
    color: #1e293b;
}
.cd-dropdown-option input[type="checkbox"] {
    display: none;
}
/* Checkbox elements inside Dropdown */
.cd-custom-checkbox {
    width: 18px;
    height: 18px;
    border: 1.5px solid #cbd5e1;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-sizing: border-box;
    transition: all 0.15s ease;
    background: #ffffff;
    flex-shrink: 0;
}
.cd-dropdown-option input[type="checkbox"]:checked + .cd-custom-checkbox {
    background: #1e3a8a;
    border-color: #1e3a8a;
}
.cd-custom-checkbox::after {
    content: '';
    width: 5px;
    height: 8px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg) scale(0);
    transition: transform 0.1s ease;
    margin-bottom: 2px;
}
.cd-dropdown-option input[type="checkbox"]:checked + .cd-custom-checkbox::after {
    transform: rotate(45deg) scale(1);
}
.cd-dropdown-option:has(input:checked) {
    background: #eff6ff;
    color: #1e40af;
    font-weight: 500;
}
.cd-no-options {
    padding: 12px;
    text-align: center;
    font-size: 13px;
    color: #94a3b8;
}
/* Submit Action Button styling to fit theme */
.cd-btn-row {
    margin-top: 28px;
    border-top: 1.5px solid #f1f5f9;
    padding-top: 20px;
}
.cd-submit {
    border: none;
    outline: none;
    background: #1e3a8a;
    color: #ffffff;
    padding: 14px 28px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    width: auto;
    display: inline-block;
}
.cd-submit:hover {
    background: #172e70;
    box-shadow: 0 4px 12px rgba(30, 58, 138, 0.15);
}
.cd-submit:active {
    transform: scale(.98);
}
/* Error highlights */
.cd-form-card label.error {
    display: block;
    margin-top: 6px;
    font-size: 12.5px;
    font-weight: 600;
    color: #dc2626;
}
.cd-form-card .cd-input-error {
    border-color: #dc2626 !important;
}
.cd-chips-container.cd-input-error {
    border-color: #dc2626 !important;
    background: #fef2f2;
}
.cd-multiselect-wrapper.cd-input-error .cd-multiselect-trigger {
    border-color: #dc2626 !important;
    background: #fef2f2;
}
@media(max-width: 768px) {
    .cd-form-card {
        padding: 25px;
        margin: 15px;
    }
    .card-head {
        flex-direction: column;
        align-items: flex-start;
    }
    .card-action,
    .cd-submit {
        width: 100%;
        text-align: center;
        margin-top: 5px;
    }
}
</style>
<!-- Scripts loading -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
$(document).ready(function(){
    const $trigger = $('#subjectSelectTrigger');
    const $dropdown = $('#subjectDropdown');
    const $wrapper = $('#subjectMultiselectWrapper');
    const $chipsContainer = $('#selectedSubjectsChips');
    const $searchInput = $('#subjectSearchInput');
    // Toggle Dropdown Display
    $trigger.on('click', function(e) {
        e.stopPropagation();
        $wrapper.toggleClass('cd-active');
        $dropdown.slideToggle(180);
        if ($wrapper.hasClass('cd-active')) {
            $searchInput.focus();
        }
    });
    // Close dropdown on click outside
    $(document).on('click', function(e) {
        if (!$wrapper.is(e.target) && $wrapper.has(e.target).length === 0) {
            closeDropdown();
        }
    });
    function closeDropdown() {
        $wrapper.removeClass('cd-active');
        $dropdown.slideUp(120);
        $searchInput.val('').trigger('input');
    }
    // Filter Options based on search query
    $searchInput.on('input', function() {
        const query = $(this).val().toLowerCase().trim();
        let matches = 0;
        $('.cd-dropdown-option').each(function() {
            const optionText = $(this).find('.cd-option-text').text().toLowerCase();
            if (optionText.includes(query)) {
                $(this).show();
                matches++;
            } else {
                $(this).hide();
            }
        });
        const $noOptions = $('.cd-no-options');
        if (matches === 0) {
            if ($noOptions.length === 0) {
                $('#subjectOptionsList').append('<div class="cd-no-options">No matching subjects found</div>');
            } else {
                $noOptions.text('No matching subjects found').show();
            }
        } else {
            $noOptions.hide();
        }
    });
    // Update Chips Container Display
    function updateSelectedChips() {
        const $checkedInputs = $('input[name="smId[]"]:checked');
        $chipsContainer.empty();
        if ($checkedInputs.length === 0) {
            $chipsContainer.append('<span class="cd-no-selection">No subjects selected</span>');
            $trigger.find('.cd-trigger-placeholder').show();
            $trigger.find('.cd-trigger-count').hide();
        } else {
            $trigger.find('.cd-trigger-placeholder').hide();
            $trigger.find('.cd-trigger-count')
                .text(`${$checkedInputs.length} Selected`)
                .css('display', 'inline-block');
            $checkedInputs.each(function() {
                const id = $(this).val();
                const name = $(this).data('name');
                const $chip = $(`
                    <div class="cd-chip-item" data-val="${id}">
                        <span>${name}</span>
                        <button type="button" class="cd-chip-remove" aria-label="Remove subject">&times;</button>
                    </div>
                `);
                $chipsContainer.append($chip);
            });
        }
        
        // Re-validate field to clear error states if validated successfully
        if ($('#subjectMappingForm').validate().element('input[name="smId[]"]')) {
            $wrapper.removeClass('cd-input-error');
            $chipsContainer.removeClass('cd-input-error');
        }
    }
    // Checkbox change triggers chip update
    $('#subjectOptionsList').on('change', 'input[name="smId[]"]', function() {
        updateSelectedChips();
    });
    // Remove chip via 'x' button
    $chipsContainer.on('click', '.cd-chip-remove', function(e) {
        e.stopPropagation();
        const valueToRemove = $(this).parent().data('val');
        const $checkbox = $(`input[name="smId[]"][value="${valueToRemove}"]`);
        
        if ($checkbox.length) {
            $checkbox.prop('checked', false).trigger('change');
        }
    });
    // Custom Validation Method for jQuery Validation (Multi-select check)
    $.validator.addMethod('atLeastOneSubjectChecked', function(value, element) {
        return $('input[name="smId[]"]:checked').length > 0;
    }, 'Please select at least one subject.');
    // Form Validation Rules and Messages
    $('#subjectMappingForm').validate({
        rules: {
            cmId: {
                required: true
            },
            emId: {
                required: true
            },
            'smId[]': {
                required: true,
                atLeastOneSubjectChecked: true
            },
            marks: {
                required: true,
                number: true,
                min: 0
            }
        },
        messages: {
            cmId: {
                required: 'Please select a class.'
            },
            emId: {
                required: 'Please select an exam.'
            },
            'smId[]': {
                required: 'Please select at least one subject.'
            },
            marks: {
                required: 'Please enter marks.',
                number: 'Please enter a valid numeric value.',
                min: 'Marks cannot be negative.'
            }
        },
        errorPlacement: function(error, element) {
            if (element.attr('name') === 'smId[]') {
                error.insertAfter('#subjectMultiselectWrapper');
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element) {
            if ($(element).attr('name') === 'smId[]') {
                $wrapper.addClass('cd-input-error');
                $chipsContainer.addClass('cd-input-error');
            } else {
                $(element).addClass('cd-input-error');
            }
        },
        unhighlight: function(element) {
            if ($(element).attr('name') === 'smId[]') {
                $wrapper.removeClass('cd-input-error');
                $chipsContainer.removeClass('cd-input-error');
            } else {
                $(element).removeClass('cd-input-error');
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});
</script>
<?php if($this->session->flashdata('success')){ ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '<?php echo $this->session->flashdata("success"); ?>',
    confirmButtonColor: '#1e3a8a',
    timer: 2500,
    showConfirmButton: false
});
</script>
<?php } ?>
<?php if($this->session->flashdata('error')){ ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '<?php echo $this->session->flashdata("error"); ?>',
    confirmButtonColor: '#dc2626'
});
</script>
<?php } ?>
