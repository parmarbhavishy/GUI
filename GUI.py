import tkinter as tk
from tkinter import ttk, filedialog, messagebox


root = tk.Tk()
root.title("Employee Registration Form")
root.geometry("800x700")
root.resizable(False, False)

title = tk.Label(
    root,
    text="Employee Registration Form",
    font=("Arial", 28, "bold")
)
title.place(x=190, y=30)

tk.Label(
    root,
    text="First Name:",
    font=("Arial", 15)
).place(x=80, y=120)

first_name = tk.Entry(
    root,
    font=("Arial", 14),
    width=45
)
first_name.place(x=270, y=115)

tk.Label(
    root,
    text="Last Name:",
    font=("Arial", 15)
).place(x=80, y=175)

last_name = tk.Entry(
    root,
    font=("Arial", 14),
    width=45
)
last_name.place(x=270, y=170)

tk.Label(
    root,
    text="Mobile Number:",
    font=("Arial", 15)
).place(x=80, y=230)

mobile = tk.Entry(
    root,
    font=("Arial", 14),
    width=45
)
mobile.place(x=270, y=225)

tk.Label(
    root,
    text="Email:",
    font=("Arial", 15)
).place(x=80, y=285)

email = tk.Entry(
    root,
    font=("Arial", 14),
    width=45
)
email.place(x=270, y=280)

tk.Label(
    root,
    text="Gender:",
    font=("Arial", 15)
).place(x=80, y=340)

gender = tk.StringVar(value="Male")

tk.Radiobutton(
    root,
    text="Male",
    variable=gender,
    value="Male",
    font=("Arial", 13)
).place(x=270, y=335)

tk.Radiobutton(
    root,
    text="Female",
    variable=gender,
    value="Female",
    font=("Arial", 13)
).place(x=360, y=335)

tk.Radiobutton(
    root,
    text="Other",
    variable=gender,
    value="Other",
    font=("Arial", 13)
).place(x=470, y=335)

tk.Label(
    root,
    text="Department:",
    font=("Arial", 15)
).place(x=80, y=395)

department = ttk.Combobox(
    root,
    values=[
        "IT",
        "HR",
        "Finance",
        "Marketing",
        "Sales",
        "Production"
    ],
    font=("Arial", 13),
    width=42,
    state="readonly"
)
department.place(x=270, y=390)
department.set("IT")

tk.Label(
    root,
    text="Designation:",
    font=("Arial", 15)
).place(x=80, y=450)

designation = ttk.Combobox(
    root,
    values=[
        "Manager",
        "Developer",
        "Team Leader",
        "Accountant",
        "Clerk",
        "HR Executive"
    ],
    font=("Arial", 13),
    width=42,
    state="readonly"
)
designation.place(x=270, y=445)
designation.set("Developer")

tk.Label(
    root,
    text="Image:",
    font=("Arial", 15)
).place(x=80, y=510)


def select_image():
    file = filedialog.askopenfilename(
        title="Select Employee Image",
        filetypes=[
            ("Image Files", "*.jpg *.jpeg *.png"),
            ("All Files", "*.*")
        ]
    )

    if file:
        image_name.config(text="Image Selected")


select_button = tk.Button(
    root,
    text="Select Image",
    font=("Arial", 12),
    command=select_image
)
select_button.place(x=270, y=505)

image_name = tk.Label(
    root,
    text="No Image Selected",
    relief="solid",
    width=20,
    height=4
)
image_name.place(x=410, y=495)

def submit():

    fname = first_name.get()
    lname = last_name.get()
    mob = mobile.get()
    mail = email.get()
    gen = gender.get()
    dept = department.get()
    desig = designation.get()

    if fname == "" or lname == "" or mob == "" or mail == "":
        messagebox.showerror(
            "Error",
            "Please fill all fields"
        )
        return

    messagebox.showinfo(
        "Success",
        "Employee Registered Successfully!\n\n"
        "First Name: " + fname +
        "\nLast Name: " + lname +
        "\nMobile: " + mob +
        "\nEmail: " + mail +
        "\nGender: " + gen +
        "\nDepartment: " + dept +
        "\nDesignation: " + desig
    )

submit_button = tk.Button(
    root,
    text="Submit",
    font=("Arial", 15, "bold"),
    width=18,
    height=1,
    command=submit
)
submit_button.place(x=300, y=610)

root.mainloop()