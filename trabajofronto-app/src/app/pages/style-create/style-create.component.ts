import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, FormControl } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { StyleService } from '../../services/style.service';
import { GenreService } from '../../services/genre.service';

@Component({
  standalone: false,
  selector: 'app-style-create',
  templateUrl: './style-create.component.html',
  styleUrls: ['./style-create.component.scss']
})
export class StyleCreateComponent implements OnInit {
  form!: FormGroup;
  genreFilterControl = new FormControl('');
  isEditMode = false;
  styleId: number | null = null;
  genres: any[] = [];
  filteredGenres: any[] = [];

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private styleService: StyleService,
    private genreService: GenreService
  ) {}

  ngOnInit(): void {
    this.form = this.fb.group({
      name: ['', Validators.required],
      descripcion: [''],
      genreid: [null, Validators.required]
    });

    this.loadGenres();

    this.genreFilterControl.valueChanges.subscribe(() => this.filterGenres());

    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.isEditMode = true;
      this.styleId = +id;
      this.loadStyle(this.styleId);
    }
  }

  loadGenres() {
    this.genreService.getAll().subscribe((data) => {
      this.genres = data;
      this.filteredGenres = [...this.genres];
    });
  }

  filterGenres() {
    const filter = this.genreFilterControl.value?.toLowerCase() || '';
    this.filteredGenres = this.genres.filter((g) =>
      g.name.toLowerCase().includes(filter)
    );
  }

  loadStyle(id: number) {
    this.styleService.getById(id).subscribe(style => {
      this.form.patchValue(style);
    });
  }

  onSubmit() {
    if (this.form.invalid) return;

    const action = this.isEditMode && this.styleId
      ? this.styleService.update(this.styleId, this.form.value)
      : this.styleService.create(this.form.value);

    action.subscribe(() => {
      this.router.navigate(['/styles']);
    });
  }

  cancel() {
    this.router.navigate(['/styles']);
  }
}
