<style>
    p {
        font-size: 2pt
    }
</style>
<!-- logo -->
<img src="" alt="Bus Travel Ticket" >
<h4>Bus Travel Ticket</h4>
<!-- route details -->
<p>Ticket no:{{$booking->ticket_number}} </p>
<p>Date of Travel: {{$booking->date_of_travel}}</p>
<p>Bus Reg No: {{$bus_reg_no ?? 'rw 521s'}}</p>
<p>From: {{$departure_station ?? 'NYABUGABO'}}</p>
<p>At: {{$departure_time ?? '10:00'}}</p>
<p>To: {{$destination_station ?? 'HUYE'}}</p>
<p>At: {{$arrival_time ?? '12:00'}}</p>

<!-- powered by palm cash -->
<p>Powered by Palm Kash</p>
<p>www.palmkash.com</p>
