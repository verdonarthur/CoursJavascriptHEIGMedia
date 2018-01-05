<?php
header("content-type:application/xml");
echo file_get_contents("http://services.heig-vd.ch/ComemSchedule/ScheduleService.ashx/GetSchedule?CourseId=".$_GET['CourseId']);