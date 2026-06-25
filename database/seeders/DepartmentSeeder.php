<?php

class DepartmentSeeder
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function run()
    {
        echo PHP_EOL;
        echo "========================================".PHP_EOL;
        echo " Department Seeder".PHP_EOL;
        echo "========================================".PHP_EOL;

        $departments = [

            ['IT Infrastructure','Enterprise IT infrastructure, server rooms and hardware management.'],
            ['Desktop Support','L1 and L2 desktop support, software installation and troubleshooting.'],
            ['Network Operations Center','24x7 monitoring of enterprise network infrastructure.'],
            ['Security Operations Center','Cybersecurity monitoring, SIEM and incident response.'],
            ['Linux Administration','Linux server administration, patching and automation.'],
            ['Windows Administration','Active Directory, Group Policy and Windows Server management.'],
            ['Cloud Operations','AWS, Azure, virtualization and cloud infrastructure.'],
            ['Database Administration','MySQL, SQL Server and PostgreSQL administration.'],
            ['Application Support','Enterprise application monitoring and troubleshooting.'],
            ['IT Procurement','Procurement of laptops, desktops, printers and networking devices.'],
            ['Asset Management','IT asset lifecycle management and inventory control.'],
            ['Information Security','ISO 27001 compliance and security governance.'],
            ['Service Desk','ITIL incident, service request and change management.'],
            ['Software Development','Enterprise software development and maintenance.'],
            ['Quality Assurance','Software testing and quality control.'],
            ['DevOps','CI/CD pipelines and deployment automation.'],
            ['Engineering','Technical engineering operations.'],
            ['Airport Operations','Airport operations and support services.'],
            ['Cargo Operations','Cargo handling and logistics systems.'],
            ['Ground Handling','Ground support operations.'],
            ['Flight Operations','Flight scheduling and operations support.'],
            ['Finance','Financial planning and accounting.'],
            ['Accounts Payable','Vendor payment processing.'],
            ['Accounts Receivable','Customer payment management.'],
            ['Human Resources','Employee lifecycle management.'],
            ['Payroll','Salary processing and compliance.'],
            ['Legal','Corporate legal services.'],
            ['Compliance','Regulatory compliance and audits.'],
            ['Administration','Office administration and facility management.'],
            ['Facilities','Building maintenance and infrastructure.'],
            ['Customer Support','Customer relationship management.'],
            ['Training','Employee learning and development.'],
            ['Business Intelligence','Data analytics and dashboards.'],
            ['Project Management Office','Project governance and execution.'],
            ['Procurement','Vendor sourcing and purchasing.'],
            ['Supply Chain','Supply chain operations.'],
            ['Warehouse','Inventory storage management.'],
            ['Transport','Transport coordination.'],
            ['Digital Transformation','Enterprise digital initiatives.'],
            ['Research & Development','Innovation and research activities.']

        ];

        $stmt = $this->db->prepare("
            INSERT INTO departments
            (
                department_name,
                department_code,
                description,
                status
            )
            VALUES
            (
                :department_name,
                :department_code,
                :description,
                :status
            )
        ");

        $count = 1;

        foreach ($departments as $department) {

            $stmt->execute([

                'department_name' => $department[0],

                'department_code' => 'DPT'.str_pad($count,3,'0',STR_PAD_LEFT),

                'description' => $department[1],

                'status' => 'Active'

            ]);

            echo "Inserted : ".$department[0].PHP_EOL;

            $count++;

        }

        echo PHP_EOL;
        echo "✔ ".($count-1)." Departments Inserted Successfully".PHP_EOL;
    }
}