## Task tracker

1. Task tracker should have a dashboard. All UberPopug Inc. employees should have access to the dashboard.
* Actor: Account
* Data: Account id
* Query: Get tasks

2. There should be an authorization service
* Actor: Account
* Command: Login to task tracker
* Data: Account credentials
* Event: Account authorized

3. Task tracker should contain only tasks

 
 ![](static/okay.png)


4. Every user have possibility to create a new task
* Actor: Account
* Command: Create a new task
* Data: Task
  * description
  * status
  * assignee -> account (no admin/manager role)
* Event: Task created
* Event: Task assigned

5. Managers or administrators should be able to assign all open tasks to users randomly my one action
* Actor: Admin/manager role account
* Command: Assign tasks
* Data: -
* Event: Task assigned (single event for every task)

6. Every user should be able to view his tasks list and mark a task as done.
* Read model: 
  * Actor: Account
  * Data: Account id
  * Query: Get tasks
* Write model:
  * Actor: Account
  * Command: Complete task
  * Data: Task id
  * Event: Task completed

## Accounting
1. There should be a separated dashboard for accounting
* Worker account read model queries:
  * get account balance
  * get account logs
* Admin/accountant account read model queries:
  * get all accounts balances
  * get all account logs
  * get daily earning statistics

2. Accounting dashboard authorization process should use the authorization service
* Actor: Account
* Command: Login to accounting
* Data: Account credentials
* Event: Account authorized

3. Every user should have his own account
* Actor: Event - Account created
* Command: Create account balance
* Data: Account id
* Event: Account balance created

4. Prices
   * money debiting occurs after a task has been assigned
     * Actor: Event - Task assigned
     * Command: Create transaction
     * Data:
       * Account id
       * Assigned task fee amount (negative value)
     * Event: Transaction created
   * money accrual occurs after task is completed
     * Actor: Event - Task completed
     * Command: Create transaction
     * Data:
       * Account id
       * Completed task amount
     * Event: Transaction created
   * negative balance is carried forward to the next day
     * Actor: Event - Transaction created
     * Command: Update account balance
     * Data:
       * Account id
       * Transaction amount
     * Event: Account balance updated

5. The dashboard should contain information about the amount of money earned by top-management for today.
* Read model: get dashboard daily data. We can make dashboard report instead of aggregate all transactions on fly. For this case:
  * Actor: Account
  * Data: Account id
  * Query: Get audit report
* Write model:
  * Actor: Event - Transaction created
  * Command: Update accounting daily report
  * Data:
    * Transaction amount
  * Event: Accounting daily report updated

6. At the end of every day it's needed to calculate the amount of money earned by every employee and send the payment information by email.
* Actor: Crontab or any scheduler 
* Command: Calculate account daily earnings
* Data:
  * Account id
* Event: Account daily earnings calculated 

* Actor: Event - Account daily earnings calculated
* Command: Send account earning notification
* Data:
    * Account id
    * Daily earning data
* Event: Account earning notification sent

7. The balance should be reset after the daily payment. The audit log should contain the payment information.
* Actor: Event: Notification sent
* Command: Create account top-up transaction
* Data:
    * Account id
    * Daily earning data
* Event: Account top-up transaction created

* Actor: Event - Account top-up transaction created
* Command: Reset account balance
* Data:
   * Account id
* Event: Account balance reset

8. The dashboard should contain the payments daily report.
* Actor: Account
* Data: Account id
* Query: Get audit report


## Analytics

Analytics service collects events and generates reports

1. There should be a separated dashboard for analytics. Only administrators should have access to it.
* Actor: Account
* Data: Account (admin role)
* Query: Get analytics report data

2. The analytics dashboard should contain information about top-management earnings and how many employees have negative balances.
* Actor: Account
* Data: Account (admin role)
* Query: Get earning daily report data

3. The analytics dashboard should contain information about the most high-cost task for a day, week and month.
* Actor: Account
* Data: Account (admin role)
* Query: Get daily highest price task report data

## Auth
* Actor: User
* Command: Create account
* Data: Account data
  * email 
* Event: Account created

* Actor: User
* Command: Authorize user
* Data: Account credentials
  * email
* Event: Account authorized
