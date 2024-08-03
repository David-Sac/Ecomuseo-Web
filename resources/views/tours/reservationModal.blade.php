<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Opcionalmente, también jQuery, Popper.js, y Bootstrap JS para funcionalidad completa -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- Versión completa de jQuery -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Modal -->
<div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalLabel">Reservar Tour</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('visits.store') }}" method="POST" id="reservationForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="tour_id" id="tour_id" value="">
                    <input type="hidden" name="tour_schedule_id" id="tour_schedule_id" value="">
                    <div class="form-group">
                        <label for="number_of_people">¿Con cuántas personas nos vas a visitar?</label>
                        <input type="number" class="form-control" id="number_of_people" name="number_of_people" placeholder="Escribe un número" required>
                    </div>
                    <div class="form-group">
                        <label>Para conocernos mejor, bríndanos el nombre de tus acompañantes y si es adulto o menor de edad:</label>
                        <div id="companions-container">
                            <div class="companion-group">
                                <input type="text" class="form-control" placeholder="Nombre" name="companions[]">
                                <button type="button" class="btn btn-outline-secondary age-button">Adulto</button>
                                <button type="button" class="btn btn-outline-secondary age-button">Menor de edad</button>
                                <button type="button" class="btn btn-outline-danger remove-companion">-</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" id="add-companion">+</button>
                    </div>
                    <div class="form-group">
                        <label>Fechas y horarios disponibles:</label>
                        <div id="modal-tour-schedules-container"></div>
                    </div>
                    <div class="form-group">
                        <label>Componentes del tour:</label>
                        <p id="modal-tour-components"></p>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">¿A qué número podemos comunicarnos contigo?</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Número" required>
                    </div>
                    <input type="hidden" name="additional_info" id="additional_info">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="reserve-button">Solicitar reserva</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#reservationForm').on('submit', function(event) {
            event.preventDefault(); // Prevenir el envío normal del formulario

            var form = $(this);
            $('#reserve-button').prop('disabled', true); // Desactivar el botón para evitar múltiples clics

            // Compilar información adicional de los acompañantes y el número de contacto
            var companions = [];
            $('#companions-container .companion-group').each(function() {
                var name = $(this).find('input[name="companions[]"]').val();
                var ageGroup = $(this).find('.age-button.selected').text();
                companions.push({ name: name, age_group: ageGroup });
            });

            var additionalInfo = {
                contact_number: $('#contact_number').val(),
                companions: companions
            };

            // Asignar la información adicional al campo hidden
            $('#additional_info').val(JSON.stringify(additionalInfo));

            $.ajax({
                type: 'POST',
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response) {
                    console.log('Reserva realizada con éxito');
                    console.log(response);
                    // Aquí puedes agregar una notificación al usuario o cerrar el modal
                    $('#reservationModal').modal('hide');
                    $('#reserve-button').prop('disabled', false); // Reactivar el botón
                },
                error: function(xhr, status, error) {
                    console.error('Error al realizar la reserva:');
                    console.error(xhr.responseText);
                    // Aquí puedes agregar una notificación de error al usuario
                    alert('Error al realizar la reserva. Inténtalo nuevamente.');
                    $('#reserve-button').prop('disabled', false); // Reactivar el botón
                }
            });
        });

        $('.reserve-button').click(function() {
            var tourId = $(this).data('tour-id');
            var tourName = $(this).data('tour-name');
            var tourSchedules = $(this).data('tour-schedules');
            var tourComponents = $(this).data('tour-components');

            $('#tour_id').val(tourId);
            $('#reservationModalLabel').text('Reservar Tour: ' + tourName);
            $('#modal-tour-components').text(tourComponents);

            var schedulesContainer = $('#modal-tour-schedules-container');
            schedulesContainer.empty();

            var schedules = tourSchedules.split(', ');
            schedules.forEach(function(schedule, index) {
                var scheduleDetails = schedule.split(' - ');
                var scheduleTime = scheduleDetails[0];
                var availableSeats = scheduleDetails[1];

                var scheduleHTML = `
                    <div class="form-check">
                        <input class="form-check-input selected-schedule" type="radio" name="selected_schedule" id="schedule${index}" value="${scheduleTime}">
                        <label class="form-check-label" for="schedule${index}">
                            ${scheduleTime} (Cupos disponibles: ${availableSeats})
                        </label>
                    </div>`;
                schedulesContainer.append(scheduleHTML);
            });

            $('#reservationModal').modal('show');
        });

        $('#add-companion').off('click').on('click', function() {
            var container = $('#companions-container');
            var newCompanion = `
                <div class="companion-group">
                    <input type="text" class="form-control" placeholder="Nombre" name="companions[]">
                    <button type="button" class="btn btn-outline-secondary age-button">Adulto</button>
                    <button type="button" class="btn btn-outline-secondary age-button">Menor de edad</button>
                    <button type="button" class="btn btn-outline-danger remove-companion">-</button>
                </div>`;
            container.append(newCompanion);
        });

        $(document).on('click', '.remove-companion', function() {
            $(this).closest('.companion-group').remove();
        });

        $(document).on('click', '.age-button', function() {
            $(this).siblings('.age-button').removeClass('selected');
            $(this).addClass('selected');
        });

        $(document).on('change', '.selected-schedule', function() {
            $('#tour_schedule_id').val($(this).val());
        });
    });
</script>

<style>
    .age-button.selected {
        background-color: #007bff;
        color: white;
    }
    .remove-companion {
        margin-left: 10px;
    }
</style>
