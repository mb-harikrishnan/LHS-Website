<div class="main">
    <div class="content">





        <!-- Statistics Boxes -->
        <div class="stats-container">

            <div class="stats-box students">
                <div class="icon">🎓</div>
                <h3>Total Students</h3>
                <h2>1250</h2>
            </div>

            <div class="stats-box teachers">
                <div class="icon">👩‍🏫</div>
                <h3>Total Teachers</h3>
                <h2>85</h2>
            </div>

            <div class="stats-box vehicles">
                <div class="icon">🚌</div>
                <h3>Total Vehicles</h3>
                <h2>18</h2>
            </div>

            <div class="stats-box cleaning">
                <div class="icon">🧹</div>
                <h3>Cleaning Staff</h3>
                <h2>24</h2>
            </div>

            <div class="stats-box drivers">
                <div class="icon">🚗</div>
                <h3>Total Drivers</h3>
                <h2>20</h2>
            </div>

        </div>






































    </div><!-- /content -->
</div>




<style>
  .stats-container{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 25px;
    padding: 30px;
}

.stats-box{
    padding: 30px 20px;
    border-radius: 20px;
    text-align: center;
    color: #fff;
    transition: 0.3s ease;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    cursor: pointer;
}

.stats-box:hover{
    transform: translateY(-8px);
}

.stats-box .icon{
    font-size: 45px;
    margin-bottom: 15px;
}

.stats-box h3{
    font-size: 20px;
    margin-bottom: 10px;
    font-weight: 600;
}

.stats-box h2{
    font-size: 38px;
    font-weight: bold;
}

/* Different Colors */

.students{
    background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.teachers{
    background: linear-gradient(135deg, #e9ba43, #38f9d7);
}

.vehicles{
    background: linear-gradient(135deg, #fa709a, #fee140);
}

.cleaning{
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.drivers{
    background: linear-gradient(135deg, #f7971e, #ffd200);
}
</style>