import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { CategoryService } from '../../services/category.service';
import { StyleService } from '../../services/style.service';

@Component({
  selector: 'app-category-create',
  standalone: false,
  templateUrl: './category-create.component.html',
  styleUrls: ['./category-create.component.scss']
})
export class CategoryCreateComponent implements OnInit {
  form!: FormGroup;
  styleFilterControl = new FormControl('');
  isEditMode = false;
  categoryId: number | null = null;
  styles: any[] = [];
  filteredStyles: any[] = [];

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private categoryService: CategoryService,
    private styleService: StyleService
  ) {}

  ngOnInit(): void {
    this.form = this.fb.group({
      name: ['', Validators.required],
      descripcion: [''],
      styleid: [null, Validators.required]
    });

    this.loadStyles();

    this.styleFilterControl.valueChanges.subscribe(() => this.filterStyles());

    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.isEditMode = true;
      this.categoryId = +id;
      this.loadCategory(this.categoryId);
    }
  }

  loadStyles() {
    this.styleService.getAll().subscribe((data) => {
      this.styles = data;
      this.filteredStyles = [...this.styles];
    });
  }

  filterStyles() {
    const filter = this.styleFilterControl.value?.toLowerCase() || '';
    this.filteredStyles = this.styles.filter((s) =>
      s.name.toLowerCase().includes(filter)
    );
  }

  loadCategory(id: number) {
    this.categoryService.getById(id).subscribe(category => {
      this.form.patchValue(category);
    });
  }

  onSubmit() {
    if (this.form.invalid) return;

    const action = this.isEditMode && this.categoryId
      ? this.categoryService.update(this.categoryId, this.form.value)
      : this.categoryService.create(this.form.value);

    action.subscribe(() => {
      this.router.navigate(['/categories']);
    });
  }

  cancel() {
    this.router.navigate(['/categories']);
  }
}
