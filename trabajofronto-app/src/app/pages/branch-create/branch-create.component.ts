import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { BranchService } from '../../services/branch.service';

@Component({
  standalone: false,
  selector: 'app-branch-create',
  templateUrl: './branch-create.component.html',
  styleUrls: ['./branch-create.component.scss']
})
export class BranchCreateComponent implements OnInit {
  branchForm!: FormGroup;
  isEditMode = false;
  branchId: number | null = null;

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private branchService: BranchService
  ) { }

  ngOnInit(): void {
    this.branchForm = this.fb.group({
      name: ['', Validators.required],
      descripcion: ['']
    });

    this.route.paramMap.subscribe(params => {
      const id = params.get('id');

      console.log(id);
      if (id) {
        this.isEditMode = true;
        this.branchId = +id;
        this.loadBranch(this.branchId);
      }
    });
  }

  loadBranch(id: number) {
    this.branchService.getById(id).subscribe(branch => {
      this.branchForm.patchValue(branch);
    });
  }

  onSubmit() {
    if (this.branchForm.invalid) return;

    if (this.isEditMode && this.branchId) {
      this.branchService.update(this.branchId, this.branchForm.value).subscribe(() => {
        this.router.navigate(['/branches']);
      });
    } else {
      this.branchService.create(this.branchForm.value).subscribe(() => {
        this.router.navigate(['/branches']);
      });
    }
  }

  cancel() {
    this.router.navigate(['/branches']);
  }
}
